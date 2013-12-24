<?
include_once __DIR__ . '/includes/config.init.php';

$errorInfo = '';
echo Zandy_Template::outString('error.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>