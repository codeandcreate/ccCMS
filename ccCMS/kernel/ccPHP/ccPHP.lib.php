<?php
/**
 * inits the ccPHP Framework
 *
 * @author Matthias Weiß <info@codeandcreate.de>
 */

if (!defined('CCPHP_BASE_PATH')) {
	define('CCPHP_BASE_PATH', realpath(dirname(__FILE__)));
}

//load ccPHP classes
		if (in_array($classPath, ['base','mixed','core','net'])) {
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
	}
}
 //datefix:
if (str_replace(".", "", phpversion()) >= 530) {
	date_default_timezone_set('Europe/Berlin');
}
