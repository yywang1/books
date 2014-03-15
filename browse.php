<?
include_once __DIR__ . '/includes/config.init.php';

//{{{ common html code
include_once __DIR__ . '/c_page.php';
$tplArray = array('common_html' => $common_html);
//}}}

//{{{ 类型、文风筛选
$tplArray['html_filterForm'] = $container['twig']->render('mod/browse/filterForm.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
		'attr_type' => $container['vars']['attr_type'],
		'attr_style' => $container['vars']['attr_style'],
	));
//}}}

//{{{ 关键字搜索
$tplArray['html_keyForm'] = $container['twig']->render('mod/browse/keyForm.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
	));
//}}}

//{{{ 热门搜索：
$tplArray['html_hotSearch'] = $container['twig']->render('mod/hotSearch.html', array(
		'WEB_ROOT' => $container['WEB_ROOT'],
		'modClass' => 'sbHot',
	));
//}}}

//{{{ fileList
include_once __DIR__ . '/includes/processor/FileListProcessor.php';
$fileList = new FileListProcessor();
$fileList->process(array(
		'container' => $container,
		'dataKey' => 'browse',
		'act' => isset($_REQUEST['act']) && $_REQUEST['act'] ? $_REQUEST['act'] : '',
		'page' => isset($_REQUEST['page']) && $_REQUEST['page'] ? $_REQUEST['page'] : '1',
		'sortBy' => isset($_REQUEST['sortby']) && $_REQUEST['sortby'] ? $_REQUEST['sortby'] : '1',
		'pageSize' => 20,
	));
$tplArray['html_fileList'] = $fileList->render(array(
		'container' => $container,
		'extendClass' => ' listing',
	));
//}}}

echo $container['twig']->render('browse.html', $tplArray);

?>