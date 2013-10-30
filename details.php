<?
include_once __DIR__ . '/includes/config.inc.php';
include_once __DIR__ . '/includes/file.func.php';

$bid = isset($_REQUEST['bid']) && $_REQUEST['bid'] ? intval($_REQUEST['bid']) : 0;
if($bid == 0) {
	redirect("index.php");
}
$file = getFileById($bid);
$file['bsummary'] = dataToHtml($file['bsummary']);

if(!empty($file)) {
	$filePath = toGb(ROOT_PATH . $file['bpath']);
	//$content = file_get_contents($filePath, 0, null, 0, 5001);
	$content = file_get_contents($filePath);
	$content = toUtf8($content);
	$lines = explode("\r\n", $content);
	$filePreview = '';
	foreach($lines as $key => $line) {
		if($key == 29) {
			$filePreview = $filePreview . '<p>' . $line . '...' . '</p>';
		} else if($key < 29) {
			$filePreview = $filePreview . '<p>' . $line . '</p>';
		} else {
			break;
		}
	}
}

echo Zandy_Template::outString('details.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>