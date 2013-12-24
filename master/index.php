<?
include_once __DIR__ . '../../includes/config.init.php';

$indexFiles = array();
$indexIdsRes = $db->query("SELECT bid FROM books WHERE beva != 1 ORDER BY bdate DESC LIMIT 5");

while($bid = mysql_fetch_row($indexIdsRes)) {
	$file = getFileById($bid[0]);
	$indexFiles[] = $file;
}

echo Zandy_Template::outString('master/index.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>