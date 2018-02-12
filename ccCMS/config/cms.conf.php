<?php
/**
 * Base configuration for ccCMS
 */

define('CMS_NAME', 'ccCMS');
define('CMS_VERSION', '0.0-testing');
define('CMS_DESCRIPTION', '');
define('CMS_COPYRIGHT', 'by Matthias Weiß');
define('CMS_JS_FILES', false); //string
define('CMS_CSS_FILES', false); //array
define('CMS_BASE_PATH', realpath(dirname(__FILE__) . "/.."));
define('CMS_INITIAL_LOGIN', false); //string
define('CMS_MODULES', false); //array

//Path to search for custom classes:
define('CCPHP_CUSTOM_CLASSES_PATH', CMS_BASE_PATH . "/customized/");

//Installation settings
include CMS_BASE_PATH . "/config/installation.conf.php";

//Default CMS_MODE is frontend
if (!defined('CMS_MODE')) {
	define('CMS_MODE', 'frontend');
}

//load the CMS Classes
include CMS_BASE_PATH . "/kernel/ccPHP/ccPHP.lib.php";
include CMS_BASE_PATH . "/kernel/ccCMS.lib.php";

//CMS Init
$cmsInstance = new ccCMS(CMS_MODE);
