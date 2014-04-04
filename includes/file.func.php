<?php
include_once __DIR__ . '/global.init.php';

function deleteOne($bid) {
	$db = $GLOBALS['db'];
	$sql_update = "UPDATE books SET bexist=0 WHERE bid=$bid";
	return $db->query($sql_update);
}

function getTmpBooks($str) {
	$db = $GLOBALS['db'];
	$tmpbooksName = '';
	$insertRes = $db->query("SELECT * FROM searches WHERE skey='$str' LIMIT 1");
	if($row = mysql_fetch_assoc($insertRes)) {
		$stimes = $row['stimes'] + 1;
		$updateSql = "UPDATE searches SET stimes=$stimes WHERE skey='$str'";
		$db->query($updateSql);
		$tmpbooksName = 'tmpbooks' . $row['sid'];
	} else {
		$insert_searches_sql = "INSERT INTO searches(skey, stimes) VALUES('$str', 1)";
		$db->query($insert_searches_sql);
		$tmpbooksName = 'tmpbooks' . mysql_insert_id();
	}
	
	if(! $db->isTableExist($tmpbooksName)) {		
		$create_tmp_books = "CREATE TEMPORARY TABLE $tmpbooksName(
			bid int NOT NULL,
			PRIMARY KEY(bid),
			bsize int, 
			beva int,
			bdate date
			)";
		$db->query($create_tmp_books);
	}
	return $tmpbooksName;
	
	//$clearSql = "TRUNCATE TABLE tmp_books";
	//$db->query($clearSql);
	
}

function insertTmpFile($bid, $tablename) {
	$db = $GLOBALS['db'];
	$is_exist_sql = "SELECT 1 FROM $tablename WHERE bid=$bid LIMIT 1";
	if($db->checkExist($is_exist_sql)) {
		return false;
	} else {
		$get_temp_res = $db->query("SELECT bid, bsize, beva, bdate FROM books WHERE bid = $bid LIMIT 1");
		while($tmpFile = mysql_fetch_assoc($get_temp_res)) {
			$insert_temp_sql = "INSERT INTO $tablename(bid, bsize, beva, bdate) VALUES(
				'" . $tmpFile['bid'] . "',
				'" . $tmpFile['bsize'] . "',
				'" . $tmpFile['beva'] . "',
				'" . $tmpFile['bdate'] . "')";
			$db->query($insert_temp_sql);
			return true;
		}
	}
}

?>