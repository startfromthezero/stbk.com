<?php
// Register Globals
if (ini_get('register_globals'))
{
	$globals = array(
		$_FILES,
		$_SERVER,
		$_REQUEST,
		$_SESSION
	);

	foreach ($globals as $global)
	{
		foreach (array_keys($global) as $key)
		{
			unset(${$key});
		}
	}
}

// Magic Quotes Fix
function walk_var_clean(&$awrv, &$awrk)
{
	$awrv = htmlspecialchars(stripslashes($awrv), ENT_QUOTES);
}

if (!empty($_GET))
{
	@array_walk_recursive($_GET, 'walk_var_clean');
}
if (!empty($_POST))
{
	@array_walk_recursive($_POST, 'walk_var_clean');
}
if (!empty($_COOKIE))
{
	@array_walk_recursive($_COOKIE, 'walk_var_clean');
}
if (!empty($_REQUEST))
{
	@array_walk_recursive($_REQUEST, 'walk_var_clean');
}
@array_walk_recursive($_SERVER, 'walk_var_clean');

// Check Document Root
if (!isset($_SERVER['DOCUMENT_ROOT']))
{
	if (isset($_SERVER['SCRIPT_FILENAME']))
	{
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

// Check Request Uri
if (!isset($_SERVER['REQUEST_URI']))
{
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1);
	if (isset($_SERVER['QUERY_STRING']))
	{
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING'];
	}
}

// Engine
require(DIR_ROOT . '/system/model.php');
require(DIR_ROOT . '/system/registry.php');
require(DIR_ROOT . '/system/controller.php');

// Common
require(DIR_ROOT . '/system/library/url.php');
require(DIR_ROOT . '/system/library/config.php');
require(DIR_ROOT . '/system/library/document.php');
require(DIR_ROOT . '/system/library/encryption.php');
require(DIR_ROOT . '/system/library/image.php');
require(DIR_ROOT . '/system/library/language.php');
require(DIR_ROOT . '/system/library/log.php');
require(DIR_ROOT . '/system/library/mail.php');
require(DIR_ROOT . '/system/library/pagination.php');
require(DIR_ROOT . '/system/library/request.php');
require(DIR_ROOT . '/system/library/response.php');
require(DIR_ROOT . '/system/library/template.php');
?>