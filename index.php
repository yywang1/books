<?
include_once __DIR__ . '/includes/file.func.php';

$getIdsSql = "SELECT bid FROM books WHERE bexist = 1 ORDER BY bdate DESC LIMIT 20";
$fileList = getFileList($getIdsSql);

echo Zandy_Template::outString('index.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>