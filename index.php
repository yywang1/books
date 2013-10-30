<?
include_once __DIR__ . '/includes/config.inc.php';
include_once __DIR__ . '/includes/file.func.php';

$getIdsSql = "SELECT bid FROM books WHERE bexist = 1 ORDER BY bdate DESC LIMIT 20";
$bids = $db->getIds($getIdsSql);

$fileList = array();
foreach($bids as $bid) {
	$file = getFileById($bid);
	$file['bsummary'] = dataToHtml($file['bsummary']);
	$fileList[] = $file;
}

echo Zandy_Template::outString('index.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>