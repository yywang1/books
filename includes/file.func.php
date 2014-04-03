<?php
include_once __DIR__ . '/global.init.php';

function updateFileById($bid, $file) {
	$db = $GLOBALS['db'];
	$sql_update_books = "UPDATE books SET 
		bname='". addslashes($file['bname']) ."',
		bauthor='". addslashes($file['bauthor']) ."',
		bsummary='". addslashes($file['bsummary']) ."',
		brole='". $file['brole'] ."',
		btype='". $file['btype'] ."',
		bstyle='". $file['bstyle'] ."',
		borig='". addslashes($file['borig']) ."'
		WHERE bid=" . $bid;
	echo $sql_update_books;
	if(!$db->query($sql_update_books)) {
		return false;
	};
	
	if(!empty($file['btags'])) {
		$sql_update_tags = "UPDATE tags SET ";
		foreach($db->attr_tags as $key=>$tag) {
			if(in_array($key, $file['btags'])) {
				$sql_update_tags .= ($key . "=1");
			} else {
				$sql_update_tags .= ($key . "=0");
			}
			if($key != ('t' . count($db->attr_tags))) {
				$sql_update_tags .= ",";
			} else {
				$sql_update_tags .= " WHERE bid=" . $bid;
			}
		}
		if(!$db->query($sql_update_tags)) {
			return false;
		};
	} else {
		$sql_update_tags = "UPDATE tags SET ";
		foreach($db->attr_tags as $key=>$tag) {
			$sql_update_tags .= ($key . "=0");
			if($key != ('t' . count($db->attr_tags))) {
				$sql_update_tags .= ",";
			} else {
				$sql_update_tags .= " WHERE bid=" . $bid;
			}
		}
		if(!$db->query($sql_update_tags)) {
			return false;
		};
	}
	return true;
}

function deleteOne($bid) {
	$db = $GLOBALS['db'];
	$sql_update = "UPDATE books SET bexist=0 WHERE bid=$bid";
	return $db->query($sql_update);
}

function verifyFile($wholename) {
	$bformat = end(explode('.', $wholename));
	if($bformat != 'txt' && $bformat != 'pdf') {
		return array(
			'code' => 1,
			'msg' => '文件类型不支持'
		);
	}
	if(strripos($wholename,' by ') == false) {
		return array(
			'code' => 2,
			'msg' => '命名格式错误'
		);
	}
	$pos_dot = strripos($wholename, '.');
	$pos_by = strripos($wholename,'by');
	$bname = trim(substr($wholename, 0, $pos_by-1));
	$bauthor = trim(substr($wholename, $pos_by+3, $pos_dot-$pos_by-3));
	if(isFileExist($bname, $bauthor)) {
		return array(
			'code' => 3,
			'msg' => '文件已存在'
		);
	}
	return array(
		'code' => 0,
		'msg' => '可以上传',
		'bname' => $bname,
		'bauthor' => $bauthor,
		'bformat' => $bformat
	);
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

function doFileEva($bid, $addEva) {
	$db = $GLOBALS['db'];
	$file = getFileById($bid);
	if(! empty($file)) {
		$beva = $file['beva'] + $addEva;
		$sql = "UPDATE books SET 
			beva='$beva'	
			WHERE bid=" . $bid;
		$db->query($sql);
	}
}

?>