<?php
include_once __DIR__ . '/BaseDao.php';

class MiscDao extends BaseDao{

	public function setRecord($key, $bid, $uid) {
		$db = $this->db();
		$sql = "SELECT * FROM `misc` WHERE bid=$bid AND uid=$uid LIMIT 1";
		$row = $db->fetchAssoc($sql);
		if(empty($row)) {
			$sql = "INSERT INTO misc(bid, uid) VALUES($bid, $uid);";
			$db->query($sql);
			$mid = mysql_insert_id();
			$row['iseva'] = 0;
		} else {
			$mid = $row['mid'];
		}
		
		if($key == 'down') {
			$sql = "UPDATE `misc` SET isdown=1, downtime='" . date('Y-m-d H:i:s') . "' WHERE mid=$mid";
			$db->query($sql);
		} else if($key == 'eva') {
			$sql = "UPDATE `misc` SET iseva=" . ($row['iseva'] ? 0 : 1) . ", evatime='" . date('Y-m-d H:i:s') . "' WHERE mid=$mid";
			$db->query($sql);
		}
	}

}
?>