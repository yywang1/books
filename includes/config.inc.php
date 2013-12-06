<?php
// database
$db_host = "127.0.0.1:3306";
$db_user = "root";
$db_pass = "root";
$db_name = "myread";

// For development or For production
$GLOBALS['JS_DEBUG'] = false;

//default        - seajs and lesscss
//seajs_standard - standard seajs without lesscss
//seajs_custom   - customseajs without lesscss
$theme = 'default';

// encoding
header("Content-type: text/html; Charset=utf-8");

//timezone
date_default_timezone_set('Asia/Shanghai');

// get root directory and default paths
define('ROOT_PATH', str_replace('includes/config.inc.php', '', str_replace('\\', '/', __FILE__)));

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
$selfurl = $_SERVER['PHP_SELF']; 
$file_name_with_postfix = substr($selfurl, strrpos($selfurl , '/')+1);  
$file_name = str_replace('.php', '', $file_name_with_postfix);

//Zandy / PHP-TEMPLATE-ENGINE
$siteConf['tplBaseDir'] = ROOT_PATH; //D:/zwnmp/web/test/books/
$siteConf['tplDir'] = ROOT_PATH . 'themes/' . $theme . '/app/';
$siteConf['cacheDir'] = ROOT_PATH . 'templates/';
$siteConf['tplCacheBaseDir'] = ROOT_PATH . 'templates/';
$siteConf['forceRefreshCache'] = false;
$siteConf['tpl_debug'] = true;
include_once __DIR__ . '/Template.php';

// SITE_DOMAIN
defined('SITE_DOMAIN') || define('SITE_DOMAIN', 'books.com');

// COOKIE_DOMAIN
defined('COOKIE_DOMAIN') || define('COOKIE_DOMAIN', '.' . SITE_DOMAIN);
defined('COOKIE_EXPIRE') || define('COOKIE_EXPIRE', 2592000); // 60*60*24*30
defined('COOKIE_PATH') || define('COOKIE_PATH', '/');
defined('SESSION_NAME') || define('SESSION_NAME', 'AIRID');

//@ini_set('memory_limit', '16M');
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

//functions
include_once __DIR__ . '/global.func.php';

//attributes of books
$attr_type = array(
	0 => '-',
	1 => '耽美',
	2 => '言情',
	3 => '魔法斗气',
	4 => '玄幻武侠',
	5 => '异世修真',
	6 => '都市异能',
	7 => '种田生活',
	8 => '历史军事',
	9 => '竞技游戏',
	10 => '未来星际',
	11 => '其它'
	);
$attr_style = array(
	0 => '-',
	1 => '轻松',
	2 => '爆笑',
	3 => '甜文',
	4 => '正剧',
	5 => '微虐',
	6 => '很虐',
	7 => '悲剧'
	);
$attr_eval = array(
	0 => '-',
	1 => '已删除',
	2 => '未读',
	3 => '一般',
	4 => '好文',
	5 => '经典'
	);
$attr_tags = array(
	't1' => '同人',
	't2' => '穿越重生',
	't3' => '异世',
	't4' => '未来',
	't5' => '末世',
	't6' => '清穿',
	't7' => '空间',
	't8' => '机甲',
	't9' => '修真',
	't10' => '异能',
	't11' => '魔法',
	't12' => '种田',
	't13' => '高干',
	't14' => '生子',
	't15' => '反琼瑶',
	't16' => 'HP',
	't17' => '红楼',
	't18' => '猎人',
	't19' => '火影',
	't20' => '死神',
	't21' => '网王',
	't22' => '武侠',
	't23' => '综漫',
	't24' => '女尊',
	't25' => 'NP',
	't26' => '父子',
	't27' => '兄弟',
	't28' => '年上',
	't29' => '年下',
	't30' => '明星'
	);
	
/*
$attr_type = arrToUtf8($attr_type);
$attr_style = arrToUtf8($attr_style);
$attr_eval = arrToUtf8($attr_eval);
$attr_tags = arrToUtf8($attr_tags);
*/

//class and functions
include_once __DIR__ . '/db.class.php';
$db = new Db($db_host, $db_user, $db_pass, $db_name);

?>