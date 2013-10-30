<?
include_once __DIR__ . '../../includes/config.inc.php';



echo Zandy_Template::outString('user/index.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>