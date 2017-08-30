<?
class ccPHP_core_backendUser extends ccPHP_base {

	private $backendUsers = [];
	
	private $backendUserConfigurationPath = CMS_BASE_PATH . "/.htpasswd";
	
	private static $instance = null;
	
    private function __construct() 
    {
    	if (file_exists($this->backendUserConfigurationPath)) {
    		$this->_loadUserData();
	    } else {
	    	ccPHP_mixed_error::fatal_error(get_class($this), 'Backend user file not found (' . $languageFile . ')');
			die();
    	}
    }
    
    public static function getInstance() 
    {
		if (self::$instance === null) {
			self::$instance = new ccPHP_core_backendUser();
		}
		return self::$instance;
    }
    
    private function _loadUserData()
    {
		$userConfiguration = file_get_contents($this->backendUserConfigurationPath);
		
		$userConfiguration = explode("\n", $userConfiguration);
		foreach($userConfiguration AS $userId => $userData) {
			if (!empty($userData)) {
				$_tmp = explode(":", $userData);
				$this->backendUsers[$userId] =  ['name' => $_tmp[0], 'password' => $_tmp[1]];
			}
		}
	}
	
	public function getBackendUsers()
	{
		return $this->backendUsers;
	}
}