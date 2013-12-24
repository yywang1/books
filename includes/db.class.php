<?php
include_once __DIR__ . '/config.init.php';
include_once __DIR__ . '/config.vars.php';

class Db {
	var $link_id = NULL;
	var $attr_tags = array();
	var $db_name = '';
	
	function __construct($dbhost, $dbuser, $dbpass, $dbname) {
		$this->init($dbhost, $dbuser, $dbpass, $dbname);
	}
	
	function init($dbhost, $dbuser, $dbpass, $dbname) {
		if($this->link_id) {
            $this->close();
        }
		if(!($this->link_id = mysql_connect($dbhost, $dbuser, $dbpass))) {
			die('Could not connect: ' . mysql_error());
		}		
		mysql_select_db($dbname, $this->link_id);
		$this->attr_tags = $GLOBALS['attr_tags'];
		$this->db_name = $dbname;
	}
	
	function query($sql) {
		return mysql_query($sql, $this->link_id);
	}
	
	function close() {
        return mysql_close($this->link_id);
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
	
	function getIds($sql) {
		$res =  $this->query($sql);
		$bids = array();
		while($row = mysql_fetch_assoc($res)) {
			$bids[] = $row['bid'];
		}
		return $bids;
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