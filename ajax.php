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
		
		$uid = $_SESSION['user']['uid'];
		$bid = $_POST['bid'];		
		$iseva = 0;
		$getRes = $db->query("SELECT * FROM `misc` WHERE bid=$bid AND uid=$uid LIMIT 1");
		if($row = mysql_fetch_assoc($getRes)) {
			if($row['iseva']) {
				$sql = "UPDATE misc SET iseva=0 WHERE mid=" . $row['mid'];			
				doFileEva($bid, -1);
			} else {
				$sql = "UPDATE misc SET iseva=1 WHERE mid=" . $row['mid'];
				$iseva = 1;
				doFileEva($bid, 1);
			}
			$r = $db->query($sql);
		} else {
			$sql = "INSERT INTO misc(bid, uid, isupload, isdown, iseva) VALUES($bid, $uid, 0, 0, 1)";
			$r = $db->query($sql);
			$iseva = 1;
			doFileEva($bid, 1);
			doMoneyAndCtbt($uid, 0, 1);
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