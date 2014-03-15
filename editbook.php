<?
include_once __DIR__ . '/includes/file.func.php';

$bid = isset($_REQUEST['bid']) && $_REQUEST['bid'] ? intval($_REQUEST['bid']) : 0;
if($bid == 0) {
	redirect("index.php");
}

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';

switch ($act) {
	case 'editInfo':
		$file['bname'] = trim($_POST['bname']);
		$file['bauthor'] = trim($_POST['bauthor']);
		$file['bsummary'] = trim($_POST['bsummary']);
		$file['brole'] = trim($_POST['brole']);
		$file['btype'] = $_POST['btype'];
		$file['bstyle'] = $_POST['bstyle'];
		$file['borig'] = $_POST['borig'];
		$file['btags'] = isset($_POST['btags']) ? $_POST['btags'] : array();
		$updatedFile = updateFileById($bid,$file);
		if($updatedFile) {
			redirect('details.php?bid='. $bid);
		} else {
			redirect('edit.php?bid=' . $bid);
		}
		break;
	default:
		$file = getFileById($bid);
		$file_tag_arr = array();
		foreach($file['btags'] as $key=>$tag) {
			$file_tag_arr[] = $key;
		}
		break;
}


echo Zandy_Template::outString('edit.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>