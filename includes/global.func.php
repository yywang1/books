<?php
include_once __DIR__ . '/config.inc.php';

function p() {
	$argvs = func_get_args();
	echo "<div style=\"text-align: left;\">\r\n";
	foreach ($argvs as $v) {
		echo "<xmp>";
		print_r($v);
		echo "</xmp>\r\n";
	}
	echo "\r\n</div>\r\n";
}

function redirect($url, $die = true) {
	if (strpos($url, 'error.php') !== false) {
		$url .= '&url=' . urlencode($_SERVER['REQUEST_URI']);
	}
	header("location: $url");
	if ($die)
		die();
}

//encoding
function toUtf8($str) {
	try{
		$encode = mb_detect_encoding($str, array('ASCII','GB2312','GBK','UTF-8')); 
		$str = iconv($encode,'utf-8//IGNORE', $str);
		return $str;
	} catch(Exception $e) {
		var_dump($e);
	}
}
function arrToUtf8($arr) {
	foreach($arr as $key => $value) {
		if(is_array($value)) {
			$arr[$key] = arrToUtf8($value);
		} else {
			$arr[$key] = toUtf8($value);
		}
	}
	return $arr;
}

function toGb($str) {
	$str = iconv('UTF-8','gbk//IGNORE', $str);
	return $str;
}

function arrToJson($arr) {
	//$arr = arrToUtf8($arr);
	return json_encode($arr);
}



//file size to [M, K, B]
function transSize($size){ 
	if($size >= 1024*1024) {
		return number_format($size/(1024*1024), 1) . ' M';
	} else if($size >= 1024) {
		return round($size/1024) . ' K';
	} else {
		return $size . ' B';
	}
}

//show summary with line feeds
function dataToHtml($str){ 
	$newStr = str_replace("\r\n", "<br>", $str);
	return $newStr;
}

//get key according to value
function valueToKey($str, $arr){
	foreach($arr as $key => $value) {
		if($str == $value) {
			return $key;
		}
	}
}

//删除url中的参数
function remove_param_in_url($url, $pkey, $append = false) {
	if (is_array($pkey)) {
		foreach ($pkey as $v) {
			$preg = '/[\?|&](' . preg_quote($v, '/') . '=([^&=]*))/';
			$m = null;
			preg_match_all($preg, $url, $m);
			if (isset($m[1]) && is_array($m[1])) {
				foreach ($m[1] as $v) {
					$url = str_replace($v, "", $url);
				}
			}
			$url = str_replace(array(
				"?&", 
				"&&"
			), array(
				"?", 
				"&"
			), $url);
			$r = rtrim($url, ' &?');
			if ($append) {
				if (strpos($r, '?') === false)
					$r .= '?';
				if (substr($r, -1) != '?' && substr($r, -1) != '&')
					$r .= '&';
			}
		}
	} else {
		$pkey = (string) $pkey;
		$preg = '/[\?|&](' . preg_quote($pkey, '/') . '=([^&=]*))/';
		$m = null;
		preg_match_all($preg, $url, $m);
		if (isset($m[1]) && is_array($m[1])) {
			foreach ($m[1] as $v) {
				$url = str_replace($v, "", $url);
			}
		}
		$url = str_replace(array(
			"?&", 
			"&&"
		), array(
			"?", 
			"&"
		), $url);
		$r = rtrim($url, ' &?');
		if ($append) {
			if (strpos($r, '?') === false)
				$r .= '?';
			if (substr($r, -1) != '?' && substr($r, -1) != '&')
				$r .= '&';
		}
	}
	return $r;
}

function getPageString($page, $url, $filesTotal, $pageSize) {
	$pageString = '';
	
	$url = remove_param_in_url($_SERVER['REQUEST_URI'], array('page'), true) . 'page=';

	if($filesTotal > $pageSize) {
		$pageTotal = ceil($filesTotal / $pageSize);
		if($page != 1) {
			$pageString .= '<a class="pre" href="' . $url . ($page - 1) . '">' . '上一页' . '</a>';
		}
		$pageString .= '<div class="sele"><span>' . $page . '</span><ul>';
		for($i = 1; $i <= $pageTotal; $i ++) {
			$pageString .= '<li><a href="' . $url . $i . '">' . $i . '</a></li>';
		}
		$pageString .= '</ul></div>';
		if($page != $pageTotal) {
			$pageString .= '<a class="next" href="' . $url . ($page + 1) . '">' . '下一页' . '</a>';
		}
	}
	return $pageString;
}

function checkLogin() {
	if(isset($_SESSION['uid']) && $_SESSION['uid'] > 0) {
		return true;
	} else {
		return false;
	}
}

?>