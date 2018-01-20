<?
	$requestPices = explode("/", substr($_SERVER['REQUEST_URI'], 1));

	if (file_exists(dirname(__FILE__)."/site/dynamic/ccCMS_dynamic.conf.php")) {
		include dirname(__FILE__)."/site/dynamic/ccCMS_dynamic.conf.php";
		if (isset($ccCMS_dynamicConf)) {
			if (empty($requestPices[0]) && isset($ccCMS_dynamicConf['home'])) {
				header("LOCATION: /" . $ccCMS_dynamicConf['home']);
				die();
			} else if (!empty($requestPices[0])) {
				if (isset($ccCMS_dynamicConf['pages'][$requestPices[0]])) {
					if (file_exists(dirname(__FILE__)."/site/dynamic/" . $ccCMS_dynamicConf['pages'][$requestPices[0]])) {
						include dirname(__FILE__)."/site/dynamic/" . $ccCMS_dynamicConf['pages'][$requestPices[0]];
						die();
					}
				} else if (in_array($requestPices[0], $ccCMS_dynamicConf['directFiles'])) {
					if (file_exists(dirname(__FILE__)."/site/" . $requestPices[0])) {
						readfile (dirname(__FILE__)."/site/" . $requestPices[0]);
						die();
					}		
				}
			}
		}
	}

	switch($requestPices[0]) {
		case 'css':
		case 'js':
		case 'img':
		case 'font':
			$fileToServe 	= dirname(__FILE__) . "/site" . $_SERVER['REQUEST_URI'];
			if (is_file($fileToServe)) {
				if ($requestPices[0] === "css") {
					header('Content-type: text/css');
				} else {
					$mimetype = mime_content_type($fileToServe);
					header('Content-type: ' . $mimetype);
				}
				$filesize = filesize($fileToServe);
				header('Content-Length: ' . $filesize);
				
				$seconds_to_cache = 3600;
				$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";
				header("Expires: $ts");
				header("Pragma: cache");
				header("Cache-Control: max-age=$seconds_to_cache");
				
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