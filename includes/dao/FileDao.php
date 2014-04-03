<?php
include_once __DIR__ . '/BaseDao.php';

class FileDao extends BaseDao{
	
	public function getBooksByBid($bid) {
		$db = $this->db();
		$sql = "SELECT * FROM books WHERE bid=$bid LIMIT 1";
		$file = $db->fetchAssoc($sql);
		return $file;
	}
	
	public function getTagsByBid($bid) {
		$db = $this->db();
		$sql = "SELECT * FROM tags WHERE bid=$bid LIMIT 1";
		$tags = array();
		$alltags = $db->fetchAssoc($sql);
		if(! empty($alltags)) {
			$attr_tags = $this->container['vars']['attr_tags'];
			foreach($alltags as $key => $value) {
				if($value == 1 && $attr_tags[$key]) {
					$tags[$key] = $attr_tags[$key];
				}
			}
		}
		return $tags;
	}
	
	public function getMiscByBidUid($bid, $uid) {
		$db = $this->db();
		$sql = "SELECT `mid`, `isupload`, `isdown`, `iseva` FROM `misc` WHERE bid=$bid AND uid=$uid LIMIT 1";
		$misc = $db->fetchAssoc($sql);
		return $misc;
	}
	
	public function getFileByBid($bid) {
		$file = $this->getBooksByBid($bid);
		$file['btags'] = $this->getTagsByBid($bid);
		$file['misc'] = isLogin() ? $this->getMiscByBidUid($bid, $_SESSION['user']['uid']) : array();
		$file['bsize'] = transSize($file['bsize']);		
		$file['bpath'] = 'files/' . $file['bauthor'] . '/' . $file['bname'] . ' by ' . $file['bauthor'] . '.' . $file['bformat'];
		$file['bsummary'] = dataToHtml($file['bsummary']);
		$vars = $this->container['vars'];
		$file['btype_lang'] = $vars['attr_type'][$file['btype']];
		$file['bstyle_lang'] = $vars['attr_style'][$file['bstyle']];
		return $file;
	}
	
	public function getBids($sql) {
		$db = $this->db();
		$bids = array();
		$rows = $db->fetchAssocArray($sql);
		foreach($rows as $row) {
			$bids[] = $row['bid'];
		}
		return $bids;
	}
	
	public function getFilesByBids($bids) {
		$fileList = array();
		foreach($bids as $bid) {
			$fileList[] = $this->getFileByBid($bid);
		}
		return $fileList;
	}
	
	public function isFileExist($bname, $bauthor) {
		$db = $this->db();
		$sql = "SELECT 1 FROM books WHERE bname='" . $bname . "' AND bauthor='" . $bauthor . "' LIMIT 1";
		return $db->checkExist($sql);
	}
	
	public function insertBooks($file) {
		$db = $this->db();
		$sql = "INSERT INTO books(bname, bauthor, bsummary, brole, bsize, btype, bstyle, beva, bexist, bformat, borig, uid, btime) VALUES(
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
			'" . $_SESSION['user']['uid'] . "',
			'" . date('Y-m-d H:i:s') . "')";
		if($db->query($sql)) {
			return mysql_insert_id();
		} else {
			return false;
		}
	}
	
	public function insertTags($bid, $btags) {
		$db = $this->db();
		$fieldStr = '';
		$valueStr = '';
		$container = $this->container;
		foreach($container['vars']['attr_tags'] as $key=>$tag) {
			$fieldStr .= (',' . $key);
			if(in_array($key, $btags)) {
				$valueStr .= ',1';
			} else {
				$valueStr .= ',0';
			}
		}
		$sql = "INSERT INTO tags(bid" . $fieldStr . ") VALUES(" . $bid . $valueStr . ")";
		if($db->query($sql)) {
			return $bid;
		} else {
			return false;
		}
		
	}
	
	public function delTagsByBid($bid, $btags) {
		$db = $this->db();
		$sql = "DELETE FROM tags WHERE bid=$bid";
		return $db->query($sql);
	}
	
	public function setBooksByBid($bid, $file) {
		$db = $this->db();
		$sql = "UPDATE books SET 
			bname='". addslashes($file['bname']) ."',
			bauthor='". addslashes($file['bauthor']) ."',
			bsummary='". addslashes($file['bsummary']) ."',
			brole='". $file['brole'] ."',
			btype='". $file['btype'] ."',
			bstyle='". $file['bstyle'] ."',
			borig='". addslashes($file['borig']) ."'
			WHERE bid=" . $bid;
		$isok = $db->query($sql);
		return $isok ? true : false;
	}
	
	public function setTagsByBid($bid, $file) {
		$db = $this->db();
		$btags = empty($file['btags']) ? array() : $file['btags'];
		$btagsInDb = $this->getTagsByBid($bid);
		if(empty($btagsInDb)) {
			if(empty($btags)) {
				$isok = true;
			} else {
				$isok = $this->insertTags($bid, $btags);
			}
		} else {
			if(empty($btags)) {
				$isok = $this->delTagsByBid($bid);
			} else {
				$sql = "UPDATE tags SET ";
				$attr_tags = $this->container['vars']['attr_tags'];
				foreach($attr_tags as $key=>$tag) {
					if(in_array($key, $btags)) {
						$sql .= ($key . "=1");
					} else {
						$sql .= ($key . "=0");
					}
					if($key != ('t' . count($attr_tags))) {
						$sql .= ",";
					} else {
						$sql .= " WHERE bid=" . $bid;
					}
				}
				$isok = $db->query($sql);
			}
		
		}
		return $isok ? true : false;
	}
	
	public function setFileByBid($bid, $file) {
		$isok_books = $this->setBooksByBid($bid, $file);
		$isok_tags = $this->setTagsByBid($bid, $file);
		return ($isok_books && $isok_tags) ? true : false;
	}
	
}
?>