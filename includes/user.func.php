<?php
include_once __DIR__ . '/config.init.php';

function insertUser($user) {
	$regMoney = 3; //注册时获取的财富
	$regCtbt = 1; //注册时获取的贡献值
	
	$db = $GLOBALS['db'];
	$insert_user_sql = "INSERT INTO users(uname, uemail, upwd, umoney, uctbt, uvalid, ulastdate, uregdate) VALUES(
		'" . $user['name'] . "',
		'" . $user['email'] . "',
		'" . $user['pwd'] . "',
		'" . $regMoney . "',
		'" . $regCtbt . "',
		'1',
		'" . date('Y-m-j') . "',
		'" . date('Y-m-j') . "')";
	if($db->query($insert_user_sql)) {
		$uid = mysql_insert_id();
		$_SESSION['user'] = getUserById($uid);
		return true;
	} else {
		return false;
	}
}

function verifyLogin($uname, $upwd) {
	$r = array(
		'code' => 0,
		'msg' =>''
	);
	$db = $GLOBALS['db'];
	$uname_res = $db->query("SELECT 1 FROM users WHERE uname='$uname' LIMIT 1");
	if(! mysql_fetch_assoc($uname_res)) {
		$r['code'] = 1;
		$r['msg'] = 'The user name is in existence.';
		return $r;
	} else {
		$upwd_res = $db->query("SELECT * FROM users WHERE uname='$uname' LIMIT 1");
		while($upwdarr = mysql_fetch_assoc($upwd_res)) {
			if($upwdarr['upwd'] != $upwd) {
				$r['code'] = 2;
				$r['msg'] = 'The password is wrong.';
				return $r;
			} else {
				$r['user'] = $upwdarr;				
			}
		}
	}
	return $r;
}

function doLogin($user) {
	$db = $GLOBALS['db'];
	$_SESSION['user'] = $user;
	$ulastdate_sql = "UPDATE users SET ulastdate='". date('Y-m-j') ."' WHERE uid=" . $user['uid'];
	$db->query($ulastdate_sql);
}

function getUserById($uid) {
	$db = $GLOBALS['db'];
	$get_user_res = $db->query("SELECT * FROM users WHERE uid=$uid LIMIT 1");
	$user = mysql_fetch_assoc($get_user_res);
	return $user;
}

function doMoneyAndCtbt($uid, $addMoney, $addCtbt) {
	$db = $GLOBALS['db'];
	$user = getUserById($uid);
	if($user) {
		$umoney = $user['umoney'] + $addMoney;
		$uctbt = $user['uctbt'] + $addCtbt;
		$sql = "UPDATE users SET 
			umoney='$umoney',
			uctbt='$uctbt'	
			WHERE uid=" . $uid;
		$db->query($sql);
	}
}

function doUserRecord($key, $bid, $uid) {
	$db = $GLOBALS['db'];
	$isupload = 0;
	$isdown = 0;
	$iseva = 0;
	if($key == 'upload') {
		$isupload = 1;
	} elseif($key == 'download') {
		$isdown = 1;
	} elseif($key == 'eva'){
		$iseva = 1;
	}
	$sql = "INSERT INTO misc(bid, uid, isupload, isdown, iseva) VALUES(
		'" . $bid . "',
		'" . $uid . "',
		'" . $isupload . "',
		'" . $isdown . "',			
		'" . $iseva . "')";
	$db->query($sql);
}

?>