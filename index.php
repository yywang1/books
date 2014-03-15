<?
include_once __DIR__ . '/includes/config.init.php';

//{{{ common html code
include_once __DIR__ . '/c_page.php';
$tplArray = array('common_html' => $common_html);
//}}}

//{{{ fileList
include_once __DIR__ . '/includes/processor/FileListProcessor.php';
$fileList = new FileListProcessor();
$fileList->process(array(
		'container' => $container,
		'dataKey' => 'index',
	));
$tplArray['html_fileList'] = $fileList->render(array(
		'container' => $container,
		'extendClass' => ' separated',
	));
//}}}

//{{{ quickNav
$tplArray['html_quickNav'] = $container['twig']->render('mod/quickNav.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
	));
//}}}

//{{{ hotSearch
$tplArray['html_hotSearch'] = $container['twig']->render('mod/hotSearch.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
		'modClass' => 'hotSearch',
	));
//}}}

echo $container['twig']->render('index.html', $tplArray);

?>