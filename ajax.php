<?
include_once __DIR__ . '/includes/file.func.php';
include_once __DIR__ . '/includes/user.func.php';

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';

switch ($act) {
	case 'doLike':
		if(! checkLogin()) {
			echo json_encode(array('code' => 1, 'msg' => 'Please login first.'));
			die();
		}
		
		$uid = $_SESSION['uid'];
		$bid = $_POST['bid'];		
		$iseva = 0;
		$getRes = $db->query("SELECT * FROM `misc` WHERE bid=$bid AND uid=$uid LIMIT 1");
		if($row = mysql_fetch_assoc($getRes)) {
			$iseva = $row['iseva'] ? 0 : 1;
			$updateSql = "UPDATE misc SET iseva=$iseva WHERE mid=" . $row['mid'];
			$r = $db->query($updateSql);
		} else {
			$insertSql = "INSERT INTO misc(bid, uid, isupload, isdown, iseva) VALUES($bid, $uid, 0, 0, 1)";
			$r = $db->query($insertSql);
			$iseva = 1;
		}
		
		if($iseva == 1) {
			doMoneyAndCtbt($uid, 0, 1);
			doFileEva($bid, 1);
		} else {
			doFileEva($bid, -1);
		}
		
		if($r) {
			echo json_encode(array('code' => 0, 'msg' => 'Success.', 'iseva' => $iseva));
		} else {
			echo json_encode(array('code' => 2, 'msg' => 'Fail.'));
		}
		break;
	case 'deleteFile':
		$bid = $_POST['bid'];
		$r = deleteOne($bid);
		echo $r;
		break;
	default:
		break;
}

?>