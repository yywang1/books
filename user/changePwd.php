<?
include_once __DIR__ . '../../includes/user.func.php';

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';

switch ($act) {
	case 'changePwd':
		$oldpwd = trim($_POST['oldpwd']);
		$r = array(
			'code' => 0,
			'msg' => ''
		);
		if($oldpwd !== $_SESSION['user']['upwd']) {
			$r['code'] = 1;
			$r['msg'] = 'The Old Password is wrong.';
			echo json_encode($r);
			die();
		}
		$upwd = trim($_POST['pwd']);
		$sql = "UPDATE users SET upwd='$upwd' WHERE uid='" . $_SESSION['user']['uid'] . "'";
		if($db->query($sql)) {
			$_SESSION['user']['upwd'] = $upwd;
			$r['code'] = 0;
			$r['msg'] = 'Success.';
		} else {
			$r['code'] = 2;
			$r['msg'] = 'Db option is wrong.';
		}
		echo json_encode($r);
		die();
		break;
	default:
		break;
}

echo Zandy_Template::outString('user/changePwd.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>