<?php
//Version
define('VERSION', '1.0.2');

//Configuration
define('DIR_SITE', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(__FILE__) : $_SERVER['DOCUMENT_ROOT']);
define('DIR_ROOT', empty($_SERVER['DOCUMENT_ROOT']) ? dirname(dirname(__FILE__)) : dirname($_SERVER['DOCUMENT_ROOT']));
require(DIR_ROOT . '/config/start.php'); //loading start for here
define('IS_MOBILE', is_mobile()); //Determine whether for mobile access

//Cache OR static HTML file
if (true) //此处加速适合于多语言多货币
{
	$_GET['islogged']  = 0;
	$_GET['ismobile']  = IS_MOBILE;
	$_GET['language']  = isset($_COOKIE['language']) ? $_COOKIE['language'] : '';
	$_GET['currency']  = isset($_COOKIE['currency']) ? $_COOKIE['currency'] : '';
	$_GET['usergroup'] = isset($_COOKIE['usergroup']) ? $_COOKIE['usergroup'] : '';
	if ($token = (isset($_COOKIE['token']) ? $_COOKIE['token'] : ''))
	{
		$_GET['islogged'] = ($token == security_token(SITE_MD5_KEY)) ? 1 : 0;
	}
	$speed = new wcore_speed('mem');
	unset($_GET['islogged'], $_GET['ismobile'], $_GET['language'], $_GET['currency'], $_GET['usergroup'], $token);
}
else //此处加速仅适应于单语言单货币
{
	$puid  = ($_SERVER["REQUEST_URI"] == '/' || $_SERVER["REQUEST_URI"] == $_SERVER["SCRIPT_NAME"]) ? 'index.html' : $_SERVER["REQUEST_URI"];
	$speed = new wcore_speed(((strpos($puid, '?') === false) ? 'file' : 'mem'), 0, $puid);
}

$html = $speed->get_data();
if (!empty($html))
{
	exit($html);
}

//Application Classes
require(DIR_ROOT . '/system/startup.php');
require(DIR_ROOT . '/system/library/currency.php');
require(DIR_ROOT . '/system/library/user.php');

//Registry And Config
$registry = new Registry();
$config   = new Config();
$registry->set('config', $config);

//Url
$url = new Url($config->get('config_use_ssl'));
$registry->set('url', $url);

//Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

//Error Handler
function error_handler($errno, $errstr, $errfile, $errline)
{
	global $log, $config;
	switch ($errno)
	{
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}

	if ($config->get('config_error_display'))
	{
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}

	if ($config->get('config_error_log'))
	{
		$log->phplog('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

set_error_handler('error_handler');

//Request
$request = new Request();
$registry->set('request', $request);

//Response
$response = new Response();
$registry->set('response', $response);
$response->setCompression($config->get('config_compression'));
$response->addHeader('Content-Type: text/html; charset=utf-8');

//Session
$session = new wcore_session(SESSION_SAVE_TYPE);
$registry->set('session', $session);

//Language
$cookcode = !empty($request->cookie['language']) ? $request->cookie['language'] : '';
$language = new Language($config->get('config_admin_language'), $cookcode, true);
$registry->set('language', $language);
$config->set('config_language_id', $language->id);

//Document
$registry->set('document', new Document());

//Currency
$registry->set('currency', new Currency($registry));

//User
$registry->set('user', new User($registry));

//Login
$registry->exectrl('common/home/login');

//Permission
$registry->exectrl('common/home/permission');

//Execute Router
$html = $registry->exectrl(isset($request->get['route']) ? $request->get['route'] : 'common/home');

//Speed Cache html
if (defined('WCORE_SPEED'))
{
	$speed->set_data($html);
	unset($speed);
}

// Output
$response->output($html);
?>