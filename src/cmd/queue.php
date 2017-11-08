#!/opt/wenv/php/bin/php
<?php
define('DIR_SITE', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(__FILE__) : $_SERVER['DOCUMENT_ROOT']);
define('DIR_ROOT', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(dirname(__FILE__)) : dirname($_SERVER['DOCUMENT_ROOT']));
require(DIR_ROOT . '/config/start.php');//程序开始处理

$shell_pid = EXEC_PATH . '/' . EXEC_NAME . '.pid';
$shell_cmd = isset($argv[1]) ? strtolower($argv[1]) : '';//获取SHELL传入的参数
switch ($shell_cmd)
{
	case 'start':
		start();
		break;
	case 'stop':
		stop();
		break;
	case 'restart':
		stop();
		sleep(5);
		start();
		break;
	default:
		echo(date('Y-m-d H:i:s') . " Usage: {start|stop|restart}\n");
}

/**
 * 开始执行 常驻内存处理
 */
function start()
{
	$mem = new modules_mem();//初始化数据模块缓存
	$mdb = $mem->mdb();//获取数据库操作对象
	file_put_contents($GLOBALS['shell_pid'], 1);
	echo(date('Y-m-d H:i:s') . ' Start Execute ' . EXEC_FILE . "\n\n");
	while (1)
	{
		/**
		 * SQL语句，获取队列中元素
		 */
		$num = 0;
		while ($sql = $mem->mem->pop($mem->mem_type_sql, 'SQL-QUEUE'))
		{
			$mdb->query($sql);
			$num++;
		}
		echo($num > 0 ? (date('Y-m-d H:i:s') . " Execute {$num} SQL Queue\n") : '');

		/**
		 * 流量卡ICCID，获取队列中元素
		 */
		$num = 0;
		while ($iccid = $mem->mem->pop($mem->mem_type_res, 'GPRS-QUEUE'))
		{
			if ($data = $mem->mem_get("GPRS-CARD-{$iccid}"))//获取流量卡最新数据更新到数据库
			{
				$mdb->update('cc_gprs_card', $data, "card_id = '{$data['card_id']}'");
				$num++;
			}
		}
		echo($num > 0 ? (date('Y-m-d H:i:s') . " Execute {$num} GPRS Queue\n") : '');

		/**
		 * 判断是否需要停止程序
		 */
		if (!file_exists($GLOBALS['shell_pid']))
		{
			break;
		}
		else
		{
			sleep(2);
		}
	}
	echo(date('Y-m-d H:i:s') . ' Stop Execute ' . EXEC_FILE . "\n\n");
}

/**
 * 停止执行
 */
function stop()
{
	if (!@unlink($GLOBALS['shell_pid']))
	{
		echo(date('Y-m-d H:i:s') . ' Can do not stop Or stopped ' . EXEC_FILE . "\n\n");
	}
}

?>