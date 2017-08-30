<?
class ccCMS_module_config extends ccCMS_module_base
{
	private $buInstance = null;
	
	function __construct()
	{
		parent::__construct();
		
		$this->buInstance = ccPHP_core_backendUser::getInstance();
	}


	public function getForm($selectedOptions)
	{
		$userConfiguration = file_get_contents(CMS_BASE_PATH . "/.htpasswd");
		
		return [
			'title' => $this->getModuleName(),
			'content' => [
				[
					'type' 	=> 'textarea',
					'name' 	=> 'htpasswd',
					'value' => $userConfiguration,
					'label' => $this->languageInstance->getText(get_class($this).":label_htpasswd")
				],
				/*[
					'type' 	=> 'label',
					'value'	=> $this->languageInstance->getText(get_class($this).":label_language")
				],*/
				[
					'type' 	=> 'input',
					'name' 	=> 'justacheckbox',
					'inputtype' => 'checkbox',
					'value'	=> "dummy",
					'checked' => false,
					'label' => "just a dummy checkbox",
				],
			]
		];
	}
}
