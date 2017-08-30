<?
	$requestPices = explode("/", substr($_SERVER['REQUEST_URI'], 1));
	
	switch($requestPices[0]) {
		case 'css':
		case 'js':
		case 'img':
			$fileToServe 	= dirname(__FILE__) . "/site" . $_SERVER['REQUEST_URI'];
			if (is_file($fileToServe)) {
				$mimetype = mime_content_type($fileToServe);
				$filesize = filesize($fileToServe);
				header('Content-type: ' . $mimetype);
				header('Content-Length: ' . $filesize);
				readfile($fileToServe);
				die();
			}
			break;
		default:
			$staticRequestCheck = $_SERVER['REQUEST_URI'];
			if (empty($staticRequestCheck)) {
				$staticRequestCheck = "/index.html";
			}
			if (preg_match('/(.*)\.html$/', $staticRequestCheck)) {
				$fileHash = md5($staticRequestCheck);
				if (file_exists(dirname(__FILE__) . "/site/static/".$fileHash.".html")) {
					readfile(dirname(__FILE__) . "/site/static/".$fileHash.".html");
					die();
				}
			}
			header("HTTP/1.0 404 Not Found");
			if (file_exists(dirname(__FILE__) . "/site/static/404.html")) {
				readfile(dirname(__FILE__) . "/site/static/404.html");
			}
			die();
	}
	print_r($requestPices);