<?
/**
 * initialisiert und läd das ccPHP Framework
 *
 * Version:
 * Autor: Matthias Weiß
 */

if (!defined('CCPHP_BASE_PATH')) {
	define('CCPHP_BASE_PATH', realpath(dirname(__FILE__)));
}

//load ccPHP classes
if ($classesPath = opendir(dirname(__FILE__) . "/classes")) {
	while (($classPath = readdir($classesPath)) !== false) {
		if (in_array($classPath, ['base','mixed','core','net'])) {
			if ($dh = opendir(dirname(__FILE__) . "/classes/" . $classPath)) {
			 	while (($file = readdir($dh)) !== false) {
			 		if (strpos($file, "ccPHP") !== false AND strpos($file, ".lib.php") !== false) {
			     		require_once dirname(__FILE__) . "/classes/" . $classPath . "/" . $file;
			 		}
			    }
			    closedir($dh);
			}
		}
	}
}


//load customized classes
if (defined('SCPHP_CUSTOM_CLASSES_PATH')) {
	if ($dh = opendir(SCPHP_CUSTOM_CLASSES_PATH)) {
	 	while (($file = readdir($dh)) !== false) {
	 		if (strpos($file, ".lib.php") !== false) {
	     		require_once SCPHP_CUSTOM_CLASSES_PATH . '/' . $file;
	 		}
	  }
	  closedir($dh);
	}
}
 //datefix:
if (str_replace(".", "", phpversion()) >= 530) {
	date_default_timezone_set('Europe/Berlin');
}
