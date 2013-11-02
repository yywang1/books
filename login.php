<?
include_once __DIR__ . '/includes/config.inc.php';
include_once __DIR__ . '/includes/user.func.php';

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';
$back = isset($_REQUEST['back']) && $_REQUEST['back'] ? $_REQUEST['back'] : $WEB_ROOT;
$tpl = 'login/login.html';

switch ($act) {
	case 'login':
		$uname = trim($_POST['name']);
		$upwd = trim($_POST['pwd']);
		$r = verifyLogin($uname, $upwd);
		if($r['code'] == 0) {
			$r['back'] = $_GET['back'];
			doLogin($r['uid']);
		}
		echo json_encode($r);
		die();
		break;
	case 'toRegister':
		$tpl = 'login/register.html';
		break;
	case 'register':
		$user = array();
		$user['name'] = trim($_POST['name']);
		$user['email'] = trim($_POST['email']);
		$user['pwd'] = trim($_POST['pwd']);
		if(insertUser($user)) {
			echo json_encode(array('code' => 0, 'back' => $_GET['back']));
		} else {
			echo json_encode(array('code' => 1));
		}
		die();
		break;
	case 'toFindPwd':
		$tpl = 'login/forgetPwd.html';
		break;
	case 'findPwd':
		$uemail = trim($_POST['email']);
		$upwd = trim($_POST['pwd']);
		$sql = "UPDATE users SET upwd='$upwd' WHERE uemail='$uemail'";
		if($db->query($sql)) {
			echo true;
		} else {
			echo false;
		}
		die();
		break;
	case 'verifyName':
		$uname = trim($_POST['name']);
		$sql = "SELECT 1 FROM users WHERE uname = '$uname' LIMIT 1";
		echo $db->checkExist($sql);
		die();		
		break;
	case 'verifyEmail':
		$uemail = trim($_POST['email']);
		$sql = "SELECT 1 FROM users WHERE uemail = '$uemail' LIMIT 1";
		echo $db->checkExist($sql);
		die();
		break;
	case 'logout':
		unset($_SESSION['uid']);
		redirect($WEB_ROOT . "login.php?back=$back");
		break;
	default:
		//redirect($back);
		break;
}
echo Zandy_Template::outString($tpl, $siteConf['tplDir'], $siteConf['cacheDir']);

?>