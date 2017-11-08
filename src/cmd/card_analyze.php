#!/opt/wenv/php/bin/php
<?php
/**
 * 流量卡数据分析处理脚本
 * 该脚本用于平台数据与联通数据校对修正，同时停止满足停号条件的流量卡使用
 *
 * 停号规则：
 * 1.流量卡的流量已过期
 * 2.联通总流量 > （赠送流量 + 累计充值流量）
 */
define('DIR_SITE', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(__FILE__) : $_SERVER['DOCUMENT_ROOT']);
define('DIR_ROOT', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(dirname(__FILE__)) : dirname($_SERVER['DOCUMENT_ROOT']));
require(DIR_ROOT . '/config/start.php');//程序开始处理

/**
 * 获取联通中间服务器上生成的流量卡数据文件
 */
$filename     = 'http://122.143.21.162/card/card_' . date('Ymd') . '.txt';
$file_headers = get_headers($filename);
if ($file_headers[0] == 'HTTP/1.1 404 Not Found')
{
	file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " No Exist File \n\n", FILE_APPEND);
	exit;
}

$content = file_get_contents($filename);
if (empty($content))
{
	file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " File Content Empty \n\n", FILE_APPEND);
	exit;
}

/**
 * 处理文件
 * 格式：ICCID,状态,月流量,总流量\n
 */
$cards = explode("\n", trim($content));
if (empty($cards))
{
	file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " File Content Error \n\n", FILE_APPEND);
	exit;
}

file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " Start Execute: Total " . count($cards) . " Cards \n\n", FILE_APPEND);
$stop_num = $update_num = 0;

/**
 * 循环比较流量卡数据，判断是否需更新停号处理
 */
$mem = new modules_mem();
$mdb = $mem->mdb();
foreach ($cards as $card)
{
	$data      = explode(',', $card);
	$card_info = getCardInfo($data[0]);
	if (empty($card_info))
	{
		continue;
	}

	/**
	 * 联通数据显示该卡已停号，但平台显示状态还是开启，则更新停号状态
	 */
	if ($data[1] == 1)
	{
		if ($card_info['unicom_stop'] == 0)
		{
			$card_info['unicom_stop'] = 1;
			$card_info['time_stop']   = date('Y-m-d H:i:s');
			updateCard($card_info);
			$stop_num++;
			file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " Stop Card {$card_info['card_iccid']}\n\n", FILE_APPEND);
		}
		continue;
	}

	$card_info['unicom_month'] = getSumMonthGprs($card_info['card_id']);

	/**
	 * 月流量大于当前的月流量，需计算流量
	 */
	if ($data[2] > $card_info['unicom_month'])
	{
		$gprs   = array(
			'month'     => $data[2],
			'total'     => $data[3],
			'is_unicom' => true
		);
		$result = modules_funs::gprsCalculate($mem, $mdb, $card_info, $gprs);
		saveMonthData($result); //计算月流量

		/**
		 * 判断剩余流量是否小于0，流量用完了则做停号处理
		 */
		if ($result['max_unused'] > 0)
		{
			$update_num++;
			continue;
		}

		if (cardStop($card_info['card_sn']))
		{
			$card_info['unicom_stop'] = 1;
			$card_info['time_stop']   = date('Y-m-d H:i:s');
			updateCard($card_info);
			$stop_num++;
			file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " Stop Card {$card_info['card_iccid']}\n\n", FILE_APPEND);
		}
		else
		{
			file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " Stop Card Failed {$card_info['card_iccid']}\n\n", FILE_APPEND);
		}
	}
}
file_put_contents(DIR_ROOT . '/cmd/card_analyze.log', date('Y-m-d H:i:s') . " Execute Complete: {$stop_num} Stop Cards, {$update_num} Update Cards \n\n", FILE_APPEND);

/**
 * 获取流量卡信息
 *
 * @param $iccid
 * @return mixed
 */
function getCardInfo($iccid)
{
	global $mem, $mdb;
	$data = $mem->mem_get("GPRS-CARD-{$iccid}");
	if (empty($data))
	{
		$data = $mdb->fetch_row("SELECT * FROM cc_gprs_card WHERE card_iccid = '{$iccid}'");
	}

	return $data;
}

/**
 * 更新流量卡信息
 *
 * @param array $data
 * @return bool
 */
function updateCard($data = array())
{
	global $mem, $mdb;
	if (!$mem->mem_set("GPRS-CARD-{$data['card_iccid']}", $data, 0))
	{
		return $mdb->update('cc_gprs_card', $data, "card_id = '{$data['card_id']}'");
	}

	$mem->mem->push($mem->mem_type_res, 'GPRS-QUEUE', $data['card_iccid']);//将流量卡ICCID加入到队列中
	return true;
}

/**
 * 记录下流量卡每月流量使用情况，使用消息队列
 *
 * @param array $data
 * @return bool|int
 */
function saveMonthData($data = array())
{
	global $mem, $mdb;
	$how_month = date('j') == 1 ? date('Ym', strtotime('last month')) : date('Ym');
	$sql       = "INSERT INTO cc_gprs_stats SET card_id = '{$data['card_id']}', how_month = '{$how_month}',
	month_used = '{$data['used_month']}', month_over = 0, time_modify = NOW()
	ON DUPLICATE KEY UPDATE month_used = '{$data['used_month']}', month_over = 0, time_modify = NOW()";
	if (!$mem->mem->push($mem->mem_type_sql, 'SQL-QUEUE', $sql))
	{
		$mdb->query($sql);

		return $mdb->affected_rows();
	}

	return true;
}

/**
 * 获取流量卡的月使用流量
 *
 * @param $card_id 流量卡编号
 * @return string
 */
function getSumMonthGprs($card_id)
{
	global $mem;
	$month = date('j') == 1 ? date('Ym', strtotime('last month')) : date('Ym');
	$sql   = "SELECT SUM(gprs_value - balance_value) FROM cc_gprs_value WHERE card_id = {$card_id} AND how_month = {$month}";

	return $mem->sdb()->fetch_one($sql);
}

/**
 * 调用联通接口停号
 *
 * @param $number
 * @return bool
 */
function cardStop($number)
{
	$params = array(
		'cmd'          => 'action',
		'serialNumber' => $number,
		'timestamp'    => time(),
		'token'        => md5(UNICOM_KEY . date('dHYm')),
		'opFlag'       => 1
	);
	$result = json_decode(wcore_utils::curl(UNICOM_URL, $params, true), true);

	return $result['status'] == 1;
}

?>