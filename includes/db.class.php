<?php
include_once __DIR__ . '/config.init.php';

class Db {
	var $link_id = NULL;
	var $attr_tags = array();
	var $db_name = '';
	
	function __construct($siteConf, $attr_tags) {
		$this->init($siteConf, $attr_tags);
	}
	
	function init($siteConf, $attr_tags) {
		if($this->link_id) {
            $this->close();
        }
		if(!($this->link_id = mysql_connect($siteConf['db_host'], $siteConf['db_user'], $siteConf['db_pass']))) {
			die('Could not connect: ' . mysql_error());
		}		
		mysql_select_db($siteConf['db_name'], $this->link_id);
		$this->attr_tags = $attr_tags;
		$this->db_name = $siteConf['db_name'];
	}
	
	function query($sql) {
		return mysql_query($sql, $this->link_id);
	}
	
	function close() {
        return mysql_close($this->link_id);
    }
	
	function fetchAssoc($sql) {
		$res =  $this->query($sql);
		$arr = array();
		while($row = mysql_fetch_assoc($res)) {
			$arr[] = $row;
		}
		return $arr;
	}
	
	function checkExist($sql) {
		$res =  $this->query($sql);
		if(mysql_fetch_assoc($res)) {
			return true;
		} else {
			return false;
		}
	}
	
	function getCount($sql) {
		$res =  $this->query($sql);
		if($row = mysql_fetch_row($res)) {
			$total = $row[0];
		}
		return $total;
	}
	
	function isTableExist($tablename) {
		$sql = "SHOW TABLES FROM " . $this->db_name;
		$res = $this->query($sql);
		while($row = mysql_fetch_row($res)) {
			if($tablename == $row[0]) {
				return true;
			}
		}
		return false;		
	}
	
}

/*
$db = new Db($db_host, $db_user, $db_pass, $db_name);
$sql = "";
$db->query($sql);
$db->close();
*/
?>