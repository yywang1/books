<?
include_once __DIR__ . '/includes/config.init.php';

$html_c_link = $container['twig']->render('mod/link.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
		'CSS_PATH' => $container['path']['css'],
	));
	
$html_c_script = $container['twig']->render('mod/script.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
		'JS_PATH' => $container['path']['js'],
		'IMG_PATH' => $container['path']['img'],
	));

if(isLogin()) {
	$html_login = '欢迎 ' . $_SESSION['user']['uname'] . '，<a href="' . $container['WEB_ROOT'] . 'login.php?act=logout&back=' . urlencode($_SERVER['REQUEST_URI']) . '">退出</a>';
} else {
	$html_login = '<a href="' . $container['WEB_ROOT'] . 'login.php?back=' . urlencode($_SERVER['REQUEST_URI']) . '">登陆</a>';
}
$html_c_header = $container['twig']->render('mod/header.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
		'html_login' => $html_login,
	));
	
$html_c_footer = $container['twig']->render('mod/footer.html', array(
	));

$common_html = array(
	'link' => $html_c_link,
	'script' => $html_c_script,
	'header' => $html_c_header,
	'footer' => $html_c_footer,
);
	
?>