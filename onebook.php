<?
include_once __DIR__ . '/includes/config.init.php';

//{{{ common html code
include_once __DIR__ . '/c_page.php';
//}}}

$bid = isset($_REQUEST['bid']) && $_REQUEST['bid'] ? intval($_REQUEST['bid']) : 0;
if($bid == 0) {
	redirect("index.php");
}

//{{{ details
include_once __DIR__ . '/includes/processor/OnebookProcessor.php';
$onebook = new OnebookProcessor();
$onebook->process(array(
		'container' => $container,
		'bid' => $bid,
	));
$html = $onebook->render(array(
		'container' => $container,
		'common_html' => $common_html,
	));

echo $html;

?>