<?
include_once __DIR__ . '../../includes/user.func.php';

if(! checkLogin()) {
	redirect($WEB_ROOT . "login.php?back=" . $_SERVER['PHP_SELF']);
}

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';

switch ($act) {
	case 'upload':
		break;
	case 'download':
		break;
	case 'favorable':
		break;
	default:
		redirect($WEB_ROOT . "user/index.php");
		break;
}

echo Zandy_Template::outString('user/records.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>