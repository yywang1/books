<?php
include_once __DIR__ . '/config.env.php';

// encoding
header("Content-type: text/html; Charset=utf-8");

//timezone
date_default_timezone_set('Asia/Shanghai');

// get root directory and default paths
define('ROOT_PATH', str_replace('includes/config.init.php', '', str_replace('\\', '/', __FILE__)));

if ($_SERVER['DOCUMENT_ROOT'] != "") {
    $WEB_ROOT = substr(realpath(dirname(__FILE__) . '/../'), strlen(realpath($_SERVER['DOCUMENT_ROOT'])));
    if (trim($WEB_ROOT, '/\\')) {
    	$WEB_ROOT = '/' . trim($WEB_ROOT, '/\\') . '/';
    } else {
    	$WEB_ROOT = '/';
    }
} else {
    $WEB_ROOT = "/";
}

$theme_path = $WEB_ROOT . 'themes/' . $theme . '/';
$CSS_PATH = $theme_path . 'css/';
$JS_PATH = $theme_path . 'js/';
$IMG_PATH = $theme_path . 'images/';

if ($theme == 'seajs_custom') {
	$selfurl = $_SERVER['PHP_SELF']; 
	$file_name_with_postfix = substr($selfurl, strrpos($selfurl , '/')+1);  
	$file_name = str_replace('.php', '', $file_name_with_postfix);
}

// Zandy / PHP-TEMPLATE-ENGINE
$siteConf['tplBaseDir'] = ROOT_PATH; //D:/zwnmp/web/test/books/
$siteConf['tplDir'] = ROOT_PATH . 'themes/' . $theme . '/app/';
$siteConf['cacheDir'] = ROOT_PATH . 'templates/';
$siteConf['tplCacheBaseDir'] = ROOT_PATH . 'templates/';
$siteConf['forceRefreshCache'] = false;
$siteConf['tpl_debug'] = true;
include_once __DIR__ . '../../vender/Template.php';

// COOKIE_DOMAIN
defined('COOKIE_DOMAIN') || define('COOKIE_DOMAIN', '.' . SITE_DOMAIN);
defined('COOKIE_EXPIRE') || define('COOKIE_EXPIRE', 2592000); // 60*60*24*30
defined('COOKIE_PATH') || define('COOKIE_PATH', '/');
defined('SESSION_NAME') || define('SESSION_NAME', 'AIRID');

// @ini_set('memory_limit', '16M');
@ini_set('session.cache_expire', 1800);
@ini_set('session.gc_maxlifetime', COOKIE_EXPIRE);
@ini_set('session.cookie_lifetime', COOKIE_EXPIRE);
@ini_set('session.use_trans_sid', 0);
@ini_set('session.use_cookies', 1);
@ini_set('session.auto_start', 0);
@ini_set('session.cookie_domain', COOKIE_DOMAIN);
@ini_set('session.cookie_path', COOKIE_PATH);
defined('SESSION_NAME') && @ini_set('session.name', SESSION_NAME);
session_start();

//common functions
include_once __DIR__ . '/global.func.php';

// 初始化数据库类
include_once __DIR__ . '/db.class.php';
$db = new Db($db_host, $db_user, $db_pass, $db_name);

?>