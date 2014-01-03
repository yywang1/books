<?php
include_once __DIR__ . '/config.init.php';

function getFileById($bid) {
	$db = $GLOBALS['db'];
	$get_file_res = $db->query("SELECT * FROM books WHERE bid=$bid LIMIT 1");
	$file = mysql_fetch_assoc($get_file_res);
	if($file) {
		$file['bsize'] = transSize($file['bsize']);		
		$file['bpath'] = 'files/' . $file['bauthor'] . '/' . $file['bname'] . ' by ' . $file['bauthor'] . '.' . $file['bformat'];
		$file['btags'] = array();
		$get_tags_res = $db->query("SELECT * FROM tags WHERE bid=$bid LIMIT 1");
		$row_tags = mysql_fetch_assoc($get_tags_res);
		if($row_tags) {
			foreach($row_tags as $field=>$value) {
				if($value == 1 && $field != 'bid') {
					foreach($db->attr_tags as $key=>$tag) {
						if($field == $key) {
							$file['btags'][$key] = $tag;
						}
					}
				}
			}
		}
		$file['isupload'] = 0;
		$file['isdown'] = 0;
		$file['iseva'] = 0;
		if(checkLogin()) {
			$uid = $_SESSION['user']['uid'];
			$getMiscRes = $db->query("SELECT * FROM `misc` WHERE bid=$bid AND uid=$uid LIMIT 1");
			if($row = mysql_fetch_assoc($getMiscRes)) {
				$file['isupload'] = $row['isupload'];
				$file['isdown'] = $row['isdown'];
				$file['iseva'] = $row['iseva'];
			}
		}
	}
	return $file;
}

function getFileList($sqlToGetIds) {
	$db = $GLOBALS['db'];
	$bids = $db->getIds($sqlToGetIds);

	$fileList = array();
	foreach($bids as $bid) {
		$file = getFileById($bid);
		$file['bsummary'] = dataToHtml($file['bsummary']);
		$fileList[] = $file;
	}
	return $fileList;
}

function moveFile($file) {
	$oldPath = $file['bpath'];
	$newFolder = ROOT_PATH . 'files/' . $file['bauthor'];
	$newPath = $newFolder . '/' . $file['bname'] . ' by ' . $file['bauthor'] . '.' . $file['bformat'];

	$oldPath = toGb($oldPath);
	$newFolder = toGb($newFolder);
	$newPath = toGb($newPath);
	
	if(! strpos($oldPath, '/temp/')) {
	}
	
	if(! file_exists($newFolder)) {
		if(! mkdir($newFolder, 0777, true)) {
			return false;
		}
	}
	if(! file_exists($newPath)) {
		if(copy($oldPath, $newPath)) {
			if(strpos($oldPath, '/temp/')) {
				unlink($oldPath);
			}
		} else {
			return false;
		}
	}
	return true;
}

function insertFile($file) {
	if(! moveFile($file)) {
		return false;
	}
	$db = $GLOBALS['db'];
	$is_exist_sql = "SELECT 1 FROM books WHERE bname='" . addslashes($file['bname']) . "' AND bauthor='" . addslashes($file['bauthor']) . "' LIMIT 1";
	if(! $db->checkExist($is_exist_sql)) {
		$insert_books_sql = "INSERT INTO books(bname, bauthor, bsummary, brole, bsize, btype, bstyle, beva, bexist, bformat, borig, bdate) VALUES(
			'" . addslashes($file['bname']) . "',
			'" . addslashes($file['bauthor']) . "',
			'" . addslashes($file['bsummary']) . "',
			'" . $file['brole'] . "',
			'" . $file['bsize'] . "',
			'" . $file['btype'] . "',
			'" . $file['bstyle'] . "',
			'0',
			'1',
			'" . $file['bformat'] . "',
			'" . $file['borig'] . "',
			'" . date('Y-m-j') . "')";
		if($db->query($insert_books_sql)) {
			$bid = mysql_insert_id();
			if(empty($file['btags'])) {
				$fieldStr = '';
				$valueStr = '';
				foreach($db->attr_tags as $key=>$tag) {
					$fieldStr .= (',' . $key);
					$valueStr .= ',0';
				}
				$sql_insert_tags = "INSERT INTO tags(bid" . $fieldStr . ") VALUES(" . $bid . $valueStr . ")";
			} else {
				$fieldStr = '';
				$valueStr = '';
				foreach($db->attr_tags as $key=>$tag) {
					$fieldStr .= (',' . $key);
					if(in_array($key, $file['btags'])) {
						$valueStr .= ',1';
					} else {
						$valueStr .= ',0';
					}						
				}
				$sql_insert_tags = "INSERT INTO tags(bid" . $fieldStr . ") VALUES(" . $bid . $valueStr . ")";
			}
			
			if($db->query($sql_insert_tags)) {
				return $bid;
			}
		}
	}
	return false;
}

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

function isFileExist($bname, $bauthor) {
	$db = $GLOBALS['db'];
	$sql = "SELECT 1 FROM books WHERE bname='" . $bname . "' AND bauthor='" . $bauthor . "' LIMIT 1";
	return $db->checkExist($sql);
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

//get all paths in the directory
function get_paths($path, &$paths) {
	if(is_dir($path)) {
		$dir = opendir($path);
		while(($file = readdir($dir)) !== false) {
			if($file != "." && $file != ".." && $file != "..") {
				get_paths($path . "\\" . $file, $paths);
			}
		}
		closedir($dir);
	}
	if(is_file($path)) {
		if(strripos($path, '~$')) {
			echo 'temporary file: ' . $path;
		} else {
			$paths[] = $path;
		}		
	}
}

function pathToUtf8($path) {
	$path = toUtf8($path);
	if(! file_exists(toGb($path))) {
		$epath = urlencode($path);
		$epath = str_replace('%E3%83%BB', '%C2%B7', $epath);
		$path = urldecode($epath);
		$path = str_replace('――', '——', $path);
	}
	return $path;
}

//get the infomation of each file
function get_files($path) {
	$paths =  array();
    get_paths($path, $paths);
	$files = array();
	foreach($paths as $path) {	
		$path = pathToUtf8($path);
		$path_arr = explode('\\', $path);
		$wholename = end($path_arr);
		$file = verifyFile($wholename);
		if($file['code'] == 0) {
			$file['bsize'] = filesize(toGb($path));
		}
		$file['bpath'] = $path;
		$files[] = $file;
	}
    return $files;
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