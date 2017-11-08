#!/opt/wenv/php/bin/php
<?php
/**
 * 流量卡数据更新脚本
 * 该脚本用于修改流量卡数据，同时修改缓存和数据库
 *
 */
define('DIR_SITE', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(__FILE__) : $_SERVER['DOCUMENT_ROOT']);
define('DIR_ROOT', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(dirname(__FILE__)) : dirname($_SERVER['DOCUMENT_ROOT']));
require(DIR_ROOT . '/config/start.php');//程序开始处理

/**
 * 停号的流量卡数据修正
 */
$mem    = new modules_mem();
$mdb    = $mem->mdb();
$result = $mdb->fetch_all('SELECT * FROM `cc_gprs_card` WHERE unicom_stop = 1 AND gprs_month > 0');
if (empty($result))
{
	echo('No Data');
}
else
{
	echo 'Total ' . count($result) . " Stop Cards\n";

	foreach ($result as $card)
	{
		$card_info = getCardInfo($card['card_iccid']);
		if (empty($card_info))
		{
			continue;
		}

		if ($card_info['unicom_total'] > 0)
		{
			$card_info['max_unused'] = $card_info['gprs_month'] - $card_info['unicom_total'];
			$card_info['used_month'] = $card_info['unicom_month'];
			$card_info['used_total'] = $card_info['unicom_total'];
			updateCard($card_info);
			continue;
		}
	}
}
exit;

/**
 * 联通总流量小于平台总流量的流量卡修正，排除非联通卡
 */
$sql    = "SELECT * FROM `cc_gprs_card` WHERE unicom_total < used_total AND unicom_stop = 0 AND batch_id != 14 AND batch_id != 30";
$result = $mdb->fetch_all($sql);
if (empty($result))
{
	echo("No Data\n");
}
else
{
	echo 'Total ' . count($result) . " Differ Cards\n";
	foreach ($result as $card)
	{
		$card_info = getCardInfo($card['card_iccid']);
		if (empty($card_info))
		{
			continue;
		}

		$card_info['gprs_month'] = $card_info['gprs_month'] == 0 ? 2048 : $card_info['gprs_month'];
		$card_info['used_month'] = $card_info['unicom_month'];
		$card_info['used_total'] = $card_info['unicom_total'];
		$card_info['max_unused'] = $card_info['gprs_month'] - $card_info['unicom_total'] + $card_info['pay_total'];
		if ($card_info['unicom_total'] > $card_info['gprs_month'])
		{
			$card_info['gprs_month'] = 0;
			$card_info['pay_unused'] = $card_info['max_unused'] < 0 ? 0 : $card_info['max_unused'];
		}
		else
		{
			$card_info['pay_unused'] = $card_info['pay_total'];
		}
		updateCard($card_info);
	}
}

/**
 * 可使用充值流量小于0的流量卡数据修正
 */
$sql    = "SELECT * FROM `cc_gprs_card` WHERE pay_unused < 0";
$result = $mdb->fetch_all($sql);
if (empty($result))
{
	echo("No Data\n");
}
else
{
	echo 'Total ' . count($result) . " Differ Cards\n";
	foreach ($result as $card)
	{
		$card_info = getCardInfo($card['card_iccid']);
		if (empty($card_info))
		{
			continue;
		}
		$card_info['pay_unused'] = 0;
		updateCard($card_info);
	}
}

/**
 * 满足停号条件的停号
 */
$sql    = "SELECT * FROM `cc_gprs_card` WHERE unicom_total > 2048 + pay_total AND unicom_stop = 0 AND gprs_month = 0";
$result = $mdb->fetch_all($sql);
if (empty($result))
{
	echo("No Data\n");
}
else
{
	echo 'Total ' . count($result) . " Stop Cards\n";
	foreach ($result as $card)
	{
		$card_info = getCardInfo($card['card_iccid']);
		if (empty($card_info))
		{
			continue;
		}
		if (cardStop($card_info['card_sn']))
		{
			$card_info['unicom_stop'] = 1;
			$card_info['time_stop']   = date('Y-m-d H:i:s');
			updateCard($card_info);
		}
	}
}

exit('Execute Complete');

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