<?
include_once __DIR__ . '/includes/file.func.php';
include_once __DIR__ . '/includes/user.func.php';

$bid = isset($_REQUEST['bid']) && $_REQUEST['bid'] ? intval($_REQUEST['bid']) : 0;
if($bid == 0) {
	redirect("index.php");
}

if(! checkLogin()) {
	redirect($WEB_ROOT . "login.php?back=" . $_SERVER['PHP_SELF']);
}


$file = getFileById($bid);
$filePath = ROOT_PATH . $file['bpath'];
$fileName = basename($filePath);

if(file_exists(toGb($filePath))) {
	header("Content-type: text/plain");
	
	$userAgent = $_SERVER["HTTP_USER_AGENT"];
	if (preg_match("/MSIE/", $userAgent)) {
		header('Content-Disposition: attachment; filename="' . toGb($fileName) . '"');  
	} else if (preg_match("/Firefox/", $userAgent)) {  
		header('Content-Disposition: attachment; filename="' . $fileName . '"');
	} else {  
		header("Content-Disposition: attachment; filename=" . $fileName);
	}

	readfile(toGb($filePath));
	
	doUserRecord('isdown', $bid, $_SESSION['user']['uid']);
	
} else {
	die();
}

?>