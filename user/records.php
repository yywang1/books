<?
include_once __DIR__ . '../../includes/user.func.php';
include_once __DIR__ . '../../includes/file.func.php';

if(! checkLogin()) {
	redirect($WEB_ROOT . "login.php?back=" . $_SERVER['PHP_SELF']);
}
$uid = $_SESSION['user']['uid'];

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';
$acts = array(
	'upload' => '上传',
	'download' => '下载',
	'favorable' => '好评',
);

switch ($act) {
	case 'upload':
		$getIdsSql = "SELECT bid FROM misc WHERE isupload=1 AND uid=$uid ORDER BY mid DESC";
		$fileList = getFileList($getIdsSql);
		break;
	case 'download':
		$getIdsSql = "SELECT bid FROM misc WHERE isdown=1 AND uid=$uid ORDER BY mid DESC";
		$fileList = getFileList($getIdsSql);
		break;
	case 'favorable':
		$getIdsSql = "SELECT bid FROM misc WHERE iseva=1 AND uid=$uid ORDER BY mid DESC";
		$fileList = getFileList($getIdsSql);
		break;
	default:
		redirect($WEB_ROOT . "index.php");
		break;
}

echo Zandy_Template::outString('user/records.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>