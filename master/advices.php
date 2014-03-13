<?
include_once __DIR__ . '../../includes/user.func.php';

if(! checkLogin()) {
	redirect($WEB_ROOT . "login.php?back=" . $_SERVER['PHP_SELF']);
}

echo Zandy_Template::outString('master/advices.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>