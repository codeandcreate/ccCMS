<?php
/**
 * inits the ccPHP Framework
 *
 * @version 0.1.1
 * @author Matthias Weiß <info@codeandcreate.de>
 */

if (!defined('CCPHP_BASE_PATH')) {
	define('CCPHP_BASE_PATH', realpath(dirname(__FILE__)));
}

//load ccPHP classes
if ($classesPath = scandir(dirname(__FILE__) . "/classes")) {
	foreach($classesPath AS $classPath) {
		if (in_array($classPath, ['base','mixed','core','net'])) {
			if ($dh = scandir(dirname(__FILE__) . "/classes/" . $classPath)) {
				foreach($dh AS $file) {
			 		if (strpos($file, "ccPHP") !== false AND strpos($file, ".lib.php") !== false) {
			     		require_once dirname(__FILE__) . "/classes/" . $classPath . "/" . $file;
			 		}
			    }
			}
		}
	}
}

//load customized classes
if (defined('CCPHP_CUSTOM_CLASSES_PATH')) {
	if ($dh = scandir(CCPHP_CUSTOM_CLASSES_PATH)) {
		foreach($dh AS $file) {
			if (strpos($file, ".lib.php") !== false) {
	    		require_once CCPHP_CUSTOM_CLASSES_PATH . '/' . $file;
			}
		}
	}
}
 //datefix:
if (str_replace(".", "", phpversion()) >= 530) {
	date_default_timezone_set('Europe/Berlin');
}
