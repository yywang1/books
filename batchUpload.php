<?
include_once __DIR__ . '/includes/file.func.php';

$act = isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '';

switch ($act) {
	case 'verifyDir':
		$dir = $_POST['dir'];
		$r = array(
			'code' => 0,
			'msg' => '',
			'legal' => array(),
			'illegal' => array(),
		);
		$filesInDir = get_files($dir);
		if(empty($filesInDir)) {
			$r['code'] = 1;
			$r['msg'] = '该目录下没有文件';
			echo arrToJson($r);
			die();
		}
		foreach($filesInDir as $file) {
			if($file['code'] == 0) {
				$r['legal'][] = $file;
			} else {
				$r['illegal'][] = $file;
			}
		}
		if(empty($r['illegal'])) {
			$r['msg'] = '共 ' . count($r['legal']) . ' 个文件可上传，文件列表如下：';
		} else {
			$r['code'] = 2;
			$r['msg'] = '共 ' . count($r['illegal']) . ' 个文件不可用，请检查：';
		}
		echo arrToJson($r);
		die();
		break;
	case 'batchUpload':
		$dir = $_POST['dir'];
		$btype = $_POST['btype'];
		$btags = isset($_POST['btags']) ? $_POST['btags'] : array();
		$r = array(
			'code' => 0,
			'legal' => array(),
			'illegal' => array(),
		);
		$filesInDir = get_files($dir);	
		foreach($filesInDir as $file) {
			$file['btype'] = $btype;
			$file['bsummary'] = '';
			$file['brole'] = '';
			$file['bstyle'] = 0;
			$file['borig'] = '';
			$file['btags'] = $btags;
			if(insertFile($file)) {
				$r['legal'][] = $file;
			} else {
				$r['illegal'][] = $file;
			}
		}
		if(empty($r['legal']) && empty($r['illegal'])) {
			$r['code'] = 1;
		}
		echo arrToJson($r);
		die();
		break;
	default:
		break;
}

echo Zandy_Template::outString('batchUpload.html', $siteConf['tplDir'], $siteConf['cacheDir']);
?>