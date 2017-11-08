#!/opt/wenv/php/bin/php
<?php
/**
 * 流量分配程序, 根据联通 当月1日零点——次月1日零点之前为一个计费周期
 * 因此分配程序需每月1日零点过1分开始执行分配流量处理
 *
 * 根据用户所购买的套餐类型去分配流量
 */
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
	file_put_contents($GLOBALS['shell_pid'], 1);
	echo(date('Y-m-d H:i:s') . ' Start Execute ' . EXEC_FILE . "\n\n");
	$mem = new modules_mem();//初始化数据模块缓存
	modules_funs::gprsAllot($mem);
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