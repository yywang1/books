<?
include_once __DIR__ . '/includes/config.inc.php';

$errorInfo = '';
echo Zandy_Template::outString('error.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>