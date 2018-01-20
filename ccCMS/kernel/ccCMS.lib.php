<?php
/**
 * inits ccCMS
 *
 * @version 0.0 - darft
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccCMS
{
	public $settingsInstance		= null;
	public $languageInstance 		= null;
	public $backendModuleInstances 	= null;

	private $loggedUser	= null;

	function __construct($cmsMode = 'frontend')
	{
		if (
			!class_exists("ccPHP_core_settings") OR
			!defined('CMS_MODULES') OR
			!defined('CMS_BASE_PATH') OR
			!defined('CMS_HTPASSWD_FILE') OR
			!defined('CMS_INITIAL_LOGIN') OR
			!defined('CMS_NAME') OR
			!defined('CMS_VERSION') OR
			!defined('CMS_DESCRIPTION') OR
			!defined('CMS_COPYRIGHT') OR
			!defined('CMS_LANGUAGE') OR
			!defined('CMS_JS_FILES') OR
			!defined('CMS_CSS_FILES') OR
			!defined('CMS_INITIAL_LOGIN') OR
			!defined('SITE_THEME')
		) {
			echo "ERROR: Required settings not set or ccPHP not initialized.";
			die();
		}

		if (
			$cmsMode == "backend" && (
				!isset($_SERVER['PHP_AUTH_USER']) OR
				(
					!file_exists(CMS_BASE_PATH . '/.htaccess') OR
					!file_exists(CMS_HTPASSWD_FILE)
				)
			)
		) {
			if ($this->initHtaccessAuth()) {
				echo CMS_NAME . ' ' . CMS_VERSION . ' first time installation successful.<br>Please reload and login with admin / admin.';
				die();
			} else {
				echo 'ERROR: can not install. Please check requirements and installed <a href="/ccCMS/phpinfo.php">installed extensions</a>.';
				die();
			}
		}

		$this->settingsInstance = new ccPHP_core_settings(
			CMS_NAME,
			'',
			CMS_DESCRIPTION,
			CMS_COPYRIGHT,
			'',
			0,
			'',
			'',
			CMS_LANGUAGE,
			CMS_JS_FILES,
			CMS_CSS_FILES
		);

		$this->loggedUser = isset($_SERVER['PHP_AUTH_USER']) ? $_SERVER['PHP_AUTH_USER'] : null;

		//load backend modules
		if ($cmsMode === 'backend') {
			if ($dh = opendir(CMS_BASE_PATH . '/kernel/modules')) {
			 	while (($file = readdir($dh)) !== false) {
			 		if (strpos($file, ".lib.php") !== false) {
			     		require_once CMS_BASE_PATH . '/kernel/modules/' . $file;
			 		}
			  }
			  closedir($dh);
			}

			$this->languageInstance = ccPHP_mixed_language::getInstance(CMS_BASE_PATH . '/language/' . CMS_LANGUAGE . '.json');

			foreach(CMS_MODULES AS $cmsModuleName) {
				$_moduleClassName = 'ccCMS_module_' . $cmsModuleName;
				if (class_exists($_moduleClassName)) {
					$this->backendModuleInstances[$cmsModuleName] = new $_moduleClassName();
				}
			}
		}
	}

	private function initHtaccessAuth()
	{
		file_put_contents(CMS_BASE_PATH . '/.htaccess', 'AuthType Basic' . "\n" . 'AuthName "' . CMS_NAME . '"' . "\n" . 'AuthUserFile ' . CMS_HTPASSWD_FILE . '' . "\n" . 'Require valid-user');
		file_put_contents(CMS_HTPASSWD_FILE, CMS_INITIAL_LOGIN . "\n");
		return true;
	}
	
	public function getUserName()
	{
		return $this->loggedUser;
	}
}
