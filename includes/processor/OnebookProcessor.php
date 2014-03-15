<?php
include_once __DIR__ . '/BaseProcessor.php';

class OnebookProcessor implements BaseProcessor {

	public function isActive() {
		return true;
	}
	
	public function process($params = array()) {
		foreach ($params as $key => $param) {
            $$key = $param;
        }
		$fileDao = $container['filedao'];
		$file = $fileDao->getFileByBid($bid);
		
		$filePath = toGb($container['ROOT_PATH'] . $file['bpath']);
		$content = file_get_contents($filePath);
		$content = toUtf8($content);
		$lines = explode("\r\n", $content);
		$filePreview = '';
		foreach($lines as $key => $line) {
			if($key == 29) {
				$filePreview = $filePreview . '<p>' . $line . '...' . '</p>';
			} else if($key < 29) {
				$filePreview = $filePreview . '<p>' . $line . '</p>';
			} else {
				break;
			}
		}
		$file['filePreview'] = $filePreview;
		$this->file = $file;
	}
	
    public function render($params = array()) {
		foreach ($params as $key => $param) {
            $$key = $param;
        }
		$params['WEB_ROOT'] = $container['WEB_ROOT'];
		$params['file'] = $this->file;
		return $container['twig']->render("onebook.html", $params);
	}
}
?>