<?
class ccCMS_module_base extends ccPHP_base {

	public $languageInstance = null;
	public $customHookClass = null;

	public function __construct()
	{
		$this->languageInstance = ccPHP_mixed_language::getInstance();

		$hookClassName = str_replace("ccCMS_module_", "custom_moduleHooks_", get_class($this));
		if (class_exists($hookClassName)) {
			$this->customHookClass = new $hookClassName();
		} else {
			$this->customHookClass = $hookClassName;
		}
	}

	public function getModuleName()
	{
		return $this->languageInstance->getText(get_class($this).":name");
	}

	public function getModuleOptions()
	{
		return [];
	}

	public function getForm($selectedOptions)
	{
		return [
			'title' => $this->getModuleName()
		];
	}
}
