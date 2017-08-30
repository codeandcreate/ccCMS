<?
	//ccCMS initialisieren:
	define('CMS_MODE', 'backend');
	include_once(dirname(__FILE__) . "/config/cms.php");

	include_once(CMS_BASE_PATH . "/kernel/template/_cms_header.inc.php");

	include_once(CMS_BASE_PATH . "/kernel/template/_cms_menu.inc.php");

	?>
		<main class="pure-u-1"></main>
	<?

	include_once(CMS_BASE_PATH . "/kernel/template/_cms_footer.inc.php");
