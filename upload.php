<?
include_once __DIR__ . '/includes/config.inc.php';
include_once __DIR__ . '/includes/file.func.php';

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';

switch ($act) {
	case 'verifyAtta':
		if ($_FILES["attachment"]["error"] > 0) {
			$data = array(
				'code' => 0,
				'msg' => $_FILES["attachment"]["error"]
			);
			echo json_encode($data);
			die();
		} else {
			$attaInfo = verifyFile($_FILES["attachment"]["name"]);
			if($attaInfo['code'] === 0) {
				$tempPath = ROOT_PATH . 'temp/' . $attaInfo['bname'] . ' by ' . $attaInfo['bauthor'] . '.' . $attaInfo['bformat'];
				move_uploaded_file($_FILES["attachment"]["tmp_name"], toGb($tempPath));
				$data = array(
					'code' => 0,
					'msg' => $attaInfo['msg'],
					'bname' => $attaInfo['bname'],
					'bauthor' => $attaInfo['bauthor'],
					'bformat' => $attaInfo['bformat'],
					'bsize' => $_FILES["attachment"]["size"],
					'bpath' => $tempPath
				);
			} else {
				$data = array(
					'code' => $attaInfo['code'],
					'msg' => $attaInfo['msg']
				);
			}
			
			echo json_encode($data);
			die();
		}
		break;
	case 'uploadNew':
		$file = array();
		$file['bname'] = $_POST['bname'];
		$file['bauthor'] = $_POST['bauthor'];
		$file['bformat'] = $_POST['bformat'];
		$file['bsize'] = $_POST['bsize'];
		$file['bsummary'] = $_POST['bsummary'];
		$file['brole'] = $_POST['brole'];
		$file['btype'] = $_POST['btype'];
		$file['bstyle'] = $_POST['bstyle'];
		$file['borig'] = $_POST['borig'];
		$file['btags'] = isset($_POST['btags']) ? $_POST['btags'] : array();
		$file['bpath'] =  $_POST['bpath'];
		$r = insertFile($file);
		if($r === true) {
			$uploadResult = true;
		} else {
			$uploadResult = false;
		}
		break;
	default:
		break;
}

echo Zandy_Template::outString('upload.html', $siteConf['tplDir'], $siteConf['cacheDir']);

?>