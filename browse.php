<?
include_once __DIR__ . '/includes/file.func.php';


$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';
$page = isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : '1';
$sortBy = isset($_REQUEST['sortby']) && $_REQUEST['sortby'] ? $_REQUEST['sortby'] : '1';
if($sortBy == 1) {
	$sortByEnd = " ORDER BY bdate DESC";
} elseif($sortBy == 2) {
	$sortByEnd = " ORDER BY beva DESC";
} elseif($sortBy == 3) {
	$sortByEnd = " ORDER BY bsize DESC";
} else {
	$sortByEnd = "";
}

$pageSize = 20;

switch ($act) {
	case 'sbfilter':
		$ftype = isset($_GET['ftype']) ? $_GET['ftype'] : 0;
		$fstyle = isset($_GET['fstyle']) ? $_GET['fstyle'] : 0;
		$condition = 'WHERE ';
		
		if($ftype > 0) {
			if(strpos($condition, "=")) {
				$condition .= " AND ";
			}
			$condition .= "btype='" . $ftype . "'";
		}
		if($fstyle > 0) {
			if(strpos($condition, "=")) {
				$condition .= " AND ";
			}
			$condition .= "bstyle='" . $fstyle . "'";
		}
		$sqlTotal = "SELECT count(*) FROM books " . $condition;
		$sqlGetIds = "SELECT bid FROM books " . $condition . $sortByEnd;
		break;
	case 'sbsearch':
		$sbfield = $_GET['sbfield'];
		$sbkey = $_GET['sbkey'];
		if($sbfield == 0) {
			$field = 'bname';
		}elseif($sbfield == 1) {
			$field = 'bauthor';
		} else {
			$field = 'brole';
		}
		$sqlTotal = "SELECT count(*) FROM books WHERE $field LIKE '%" . $sbkey . "%'";
		$sqlGetIds = "SELECT bid FROM books WHERE $field LIKE '%" . $sbkey . "%'" . $sortByEnd;
		break;
	case 'hsearch':
		$hkeystr = $_GET['hkey'];
		$hkeys = explode(' ', $hkeystr);
		$tmpbooks = getTmpBooks($hkeystr);
		//var_dump($tmpbooks);
		//die();
		foreach($hkeys as $hkey) {
			if(in_array($hkey, $attr_tags)) {
				$field = valueToKey($hkey, $attr_tags);
				$tmpSql = "SELECT bid FROM tags WHERE $field = 1";
			} elseif(in_array($hkey, $attr_type)) {
				$tmpSql = "SELECT bid FROM books WHERE btype = '" . valueToKey($hkey, $attr_type) . "'";
			} elseif(in_array($hkey, $attr_style)) {
				$tmpSql = "SELECT bid FROM books WHERE bstyle = '" . valueToKey($hkey, $attr_style) . "'";
			} else {
				$tmpSql = '';
			}
			if($tmpSql != '') {
				$tmpRes = $db->query($tmpSql);
				while($row = mysql_fetch_row($tmpRes)) {
					insertTmpFile($row[0], $tmpbooks);
				}
			}
			$tmpSql_sub = "SELECT bid FROM books WHERE (bname LIKE '%" . $hkey . "%') OR (bauthor LIKE '%" . $hkey . "%') OR (brole LIKE '%" . $hkey . "%')";
			$tmpSubRes = $db->query($tmpSql_sub);
			while($row = mysql_fetch_row($tmpSubRes)) {
				insertTmpFile($row[0], $tmpbooks);
			}
		}
		$sqlTotal = "SELECT count(*) FROM $tmpbooks";
		$sqlGetIds = "SELECT bid FROM $tmpbooks" . $sortByEnd;
		break;
	default:
		$sqlTotal = "SELECT count(*) FROM books WHERE bexist=1";
		$sqlGetIds = "SELECT bid FROM books WHERE bexist=1" . $sortByEnd;
		break;
}

$fileList = array();

$filesTotal = $db->getCount($sqlTotal);
$bids = $db->getIds($sqlGetIds);

$pageStart = ($page - 1) * $pageSize;
$pageEnd = $page * $pageSize - 1;
foreach($bids as $key => $bid) {
	if($key >= $pageStart && $key <= $pageEnd) {
		$file = getFileById($bid);
		$fileList[] = $file;
	}
}

$pageTotal = ($filesTotal == 0) ? 0 : ceil($filesTotal / $pageSize);
$url = $_SERVER['REQUEST_URI'];
$pageString = getPageString($page, $url, $filesTotal, $pageSize);

$sortByUrl = remove_param_in_url($url, array('sortby', 'page'), true);
$sortByDate = $sortByUrl . 'sortby=1';
$sortByEva = $sortByUrl . 'sortby=2';
$sortBySize = $sortByUrl . 'sortby=3';

echo Zandy_Template::outString('browse.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>