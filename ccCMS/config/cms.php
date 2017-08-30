<?
	define('CMS_NAME', 'ccCMS');
	define('CMS_VERSION', '0.0-testing');
	define('CMS_DESCRIPTION', '');
	define('CMS_COPYRIGHT', 'by Matthias Weiß');
	define('CMS_JS_FILES', ['smdQS.js', 'ccCMS.js', 'ccCMS_form.js']);
	define('CMS_CSS_FILES', ['pure-min.css', 'ccCMS.css']);
	define('CMS_BASE_PATH', realpath(dirname(__FILE__) . "/.."));
	define('CMS_INITIAL_LOGIN', 'admin:$apr1$hqvjs.Qx$dJ6REZvbP/nPo40LtBxuo.');
	define('CMS_MODULES', ['pages','posts','images','object','config']);

	if (!defined('CMS_MODE')) {
		define('CMS_MODE', 'frontend');
	}

	define('SCPHP_CUSTOM_CLASSES_PATH', CMS_BASE_PATH . "/customized/");


	include CMS_BASE_PATH . "/config/installation.php";

	include CMS_BASE_PATH . "/kernel/ccPHP/ccPHP.lib.php";
	include CMS_BASE_PATH . "/kernel/ccCMS.lib.php";

	$cmsInstance = new ccCMS(CMS_MODE);
