<?php
include_once __DIR__ . '/BaseDao.php';

class UserDao extends BaseDao{
	
	public function getBooksByBid($bid) {
		$db = $this->db();
		$sql = "SELECT * FROM books WHERE bid=$bid LIMIT 1";
		$file = $db->fetchAssoc($sql);
		return $file[0];
	}
	
	public function getTagsByBid($bid) {
		$db = $this->db();
		$sql = "SELECT * FROM tags WHERE bid=$bid LIMIT 1";
		$tags = $db->fetchAssoc($sql);
		return $tags[0];
	}
	
	public function getMiscByBidUid($bid, $uid) {
		$db = $this->db();
		$sql = "SELECT `mid`, `isupload`, `isdown`, `iseva` FROM `misc` WHERE bid=$bid AND uid=$uid LIMIT 1";
		$misc = $db->fetchAssoc($sql);
		return $misc[0];
		
	}
	
	public function getBids($sql) {
		$db = $this->db();
		$bids = array();
		$rows = $db->fetchAssoc($sql);
		foreach($rows as $row) {
			$bids[] = $row['bid'];
		}
		return $bids;
	}
	
	public function getFileByBid($bid) {
		$file = $this->getBooksByBid($bid);
		$file['tags'] = $this->getTagsByBid($bid);
		$file['misc'] = isLogin() ? $this->getMiscByBidUid($bid, $_SESSION['user']['uid']) : array();
		$file['bsize'] = transSize($file['bsize']);		
		$file['bpath'] = 'files/' . $file['bauthor'] . '/' . $file['bname'] . ' by ' . $file['bauthor'] . '.' . $file['bformat'];
		$file['bsummary'] = dataToHtml($file['bsummary']);
		$vars = $this->container['vars'];
		$file['btype_lang'] = $vars['attr_type'][$file['btype']];
		$file['bstyle_lang'] = $vars['attr_style'][$file['bstyle']];
		return $file;
	}
	
	public function getFilesByBids($bids) {
		$fileList = array();
		foreach($bids as $bid) {
			$fileList[] = $this->getFileByBid($bid);
		}
		return $fileList;
	}
	
}
?>