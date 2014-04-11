<?php
include_once __DIR__ . '/BaseProcessor.php';

class FileListProcessor implements BaseProcessor {
	
	public function build_orderby($sortBy, $isJoin) {
		if($sortBy == 1) {
			$orderby = " ORDER BY " . ($isJoin ? 'b.' : '') . "btime DESC";
		} elseif($sortBy == 2) {
			$orderby = " ORDER BY " . ($isJoin ? 'b.' : '') . "bsize DESC";
		} elseif($sortBy == 3) {
			$orderby = " ORDER BY " . ($isJoin ? 'be.' : '') . "beva DESC";
		} else {
			$orderby = "";
		}
		return $orderby;
	}

	public function build_sql_sbfilter($sortBy) {
		$orderby = $this->build_orderby($sortBy, true);
		
		$ftype = isset($_GET['ftype']) ? $_GET['ftype'] : 0;
		$fstyle = isset($_GET['fstyle']) ? $_GET['fstyle'] : 0;
		$condition = 'WHERE ';
		if($ftype > 0) {
			if(strpos($condition, "=")) {
				$condition .= " AND ";
			}
			$condition .= "b.btype=" . $ftype;
		}
		if($fstyle > 0) {
			if(strpos($condition, "=")) {
				$condition .= " AND ";
			}
			$condition .= "b.bstyle=" . $fstyle;
		}
		
		$sqls = array();
		$sqls['getTotal'] = "SELECT count(*) FROM books b " . $condition;
		$sqls['getIds'] = "SELECT b.bid FROM books b
					LEFT JOIN books_extra be
					ON b.bid=be.bid " . $condition . $orderby;
		return $sqls;
	}
	
	public function build_sql_sbsearch($sortBy) {
		$orderby = $this->build_orderby($sortBy, true);
		$sbfield = $_GET['sbfield'];
		$sbkey = $_GET['sbkey'];
		if($sbfield == 0) {
			$field = 'bname';
		}elseif($sbfield == 1) {
			$field = 'bauthor';
		} else {
			$field = 'brole';
		}
		
		$sqls = array();
		$sqls['getTotal'] = "SELECT count(*) FROM books WHERE $field LIKE '%" . $sbkey . "%'";
		$sqls['getIds'] = "SELECT b.bid FROM books b
					LEFT JOIN books_extra be
					ON b.bid=be.bid WHERE b.$field LIKE '%" . $sbkey . "%'" . $orderby;
		return $sqls;
	}
	
	public function build_sql_hsearch($sortBy, $container) {
		$orderby = $this->build_orderby($sortBy, false);
		
		$filedao = $container['filedao'];
		$searchdao = $container['searchdao'];
		
		$hkeystr = $_GET['hkey'];
		$sid = $searchdao->setRecord($hkeystr);
		
		$cache_file = $searchdao->getCacheFile($sid);
		if(! $cache_file) {
			$attr_tags = $container['vars']['attr_tags'];
			$attr_type = $container['vars']['attr_type'];
			$attr_style = $container['vars']['attr_style'];
			
			$hkeys = explode(' ', $hkeystr);
			foreach($hkeys as $hkey) {
				if(in_array($hkey, $attr_tags)) {
					$sql = "SELECT bid FROM tags WHERE " . getKeyByValue($hkey, $attr_tags) . " = 1";
				} elseif(in_array($hkey, $attr_type)) {
					$sql = "SELECT bid FROM books WHERE btype = '" . getKeyByValue($hkey, $attr_type) . "'";
				} elseif(in_array($hkey, $attr_style)) {
					$sql = "SELECT bid FROM books WHERE bstyle = '" . getKeyByValue($hkey, $attr_style) . "'";
				} else {
					$sql = '';
				}
				$bids = ($sql != '') ? $filedao->getBids($sql) : array();
				$sql_like = "SELECT bid FROM books WHERE (bname LIKE '%" . $hkey . "%') OR (bauthor LIKE '%" . $hkey . "%') OR (brole LIKE '%" . $hkey . "%')";
				$bids_like = $filedao->getBids($sql_like);
				$bids = array_merge($bids, $bids_like);
				$cache_file = $searchdao->createCacheFile($sid, $bids);
			}
		}
		
		$tmpbooks = $searchdao->createTmpBooks($sid, $cache_file);
		
		$sqls = array();
		$sqls['getTotal'] = "SELECT count(*) FROM $tmpbooks";
		$sqls['getIds'] = "SELECT bid FROM $tmpbooks" . $orderby;
		return $sqls;
	}

	public function build_sql_default($sortBy) {
		$orderby = $this->build_orderby($sortBy, true);
		$sqls = array();
		$sqls['getTotal'] = "SELECT count(*) FROM books WHERE bexist=1";
		$sqls['getIds'] = "SELECT b.bid FROM books b
					LEFT JOIN books_extra be
					ON b.bid=be.bid WHERE bexist=1" . $orderby;
		return $sqls;
	}
	
	public function isActive() {
		return true;
	}
	
	public function process($params = array()) {
		foreach ($params as $key => $param) {
            $$key = $param;
        }
		$this->html_listFilter = '';
		$this->html_pageString = '';
		
		$filedao = $container['filedao'];
		
		if($dataKey == 'index') {
			$sqlGetIds = "SELECT bid,bname FROM books WHERE bexist = 1 ORDER BY btime DESC LIMIT 20";
			$bids = $filedao->getBids($sqlGetIds);
		}
		if($dataKey == 'browse') {
			switch ($act) {
				case 'sbfilter':
					$sqls = $this->build_sql_sbfilter($sortBy);
					break;
				case 'sbsearch':
					$sqls = $this->build_sql_sbsearch($sortBy);
					break;
				case 'hsearch':
					$sqls = $this->build_sql_hsearch($sortBy, $container);
					break;
				default:
					$sqls = $this->build_sql_default($sortBy, $container);
					break;
			}
			
			//数据库查询游标
			$sqlGetTotal = $sqls['getTotal'];
			$filesTotal = $container['db']->getCount($sqlGetTotal);
			if($filesTotal > $pageSize) {
				$limitoffset = " LIMIT $pageSize OFFSET " . ($page - 1) * $pageSize;
			} else {
				$limitoffset = "";
			}
			
			$sqlGetIds = $sqls['getIds'] . $limitoffset;
			$bids = $filedao->getBids($sqlGetIds);
			
			$url = $_SERVER['REQUEST_URI'];
			$pageTotal = ($filesTotal == 0) ? 0 : ceil($filesTotal / $pageSize);
			$this->html_pageString = getPageString($page, $url, $filesTotal, $pageSize);			
			$this->html_listFilter = $container['twig']->render("browse/listFilter.html", array(
					'sortByUrl' => remove_param_in_url($url, array('sortby', 'page'), true),
					'sortBy' => $sortBy,
					'filesTotal' => $filesTotal,
					'pageTotal' => $pageTotal,
				));
		}
		
		$this->fileList = $filedao->getFilesByBids($bids);
	}
	
    public function render($params = array()) {
		foreach ($params as $key => $param) {
            $$key = $param;
        }
		$params['WEB_ROOT'] = $container['WEB_ROOT'];
		$params['html_listFilter'] = $this->html_listFilter;
		$params['html_pageString'] = $this->html_pageString;
		$params['fileList'] = $this->fileList;
		return $container['twig']->render("mod/fileList.html", $params);
	}
}
?>