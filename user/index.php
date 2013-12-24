<?
include_once __DIR__ . '../../includes/config.init.php';



echo Zandy_Template::outString('user/index.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>