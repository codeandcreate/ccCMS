<?
class ccCMS_module_pages extends ccCMS_module_base {

	private $availablePages = [];

	private $formFields = [
	    [
	      'name' => 'url',
	      'type' => 'input'
	    ],
	    [
	      'name' => 'title',
	      'type' => 'input'
	    ],
	    [
	      'name' => 'content',
	      'type' => 'textarea',
		  'rows' => 30
	    ]
	];

	function __construct()
	{
		parent::__construct();
		$this->_listPages();

		if ($this->customHookClass !== null && isset($this->customHookClass->formFields)) {
			$this->formFields = $this->customHookClass->formFields;
		}
	}

	private function _loadPageConfig($pageFileName)
	{
		if (file_exists(CMS_CONFIG_PAGES_PATH . "/" . $pageFileName)) {
			return json_decode(file_get_contents(CMS_CONFIG_PAGES_PATH . "/" . $pageFileName));
		} else {
	    	ccPHP_mixed_error::fatal_error(get_class($this), 'Can\'t load page config (' . $pageFileName . ')');
			die();
    	}
	}

	private function _listPages()
	{
		if ($dh = opendir(CMS_CONFIG_PAGES_PATH)) {
			while (($file = readdir($dh)) !== false) {
				if (strpos($file, ".json") !== false) {
					$this->availablePages[] = [
						'file' => $file,
						'name' => str_replace(".json", "", $file)
					];
				}
			}
			closedir($dh);
		}
	}

	public function getForm($restData)
	{
		$restObject = json_decode($restData);
		$selectedOptions = [];

		if (is_object($restObject)) {
			foreach($restObject AS $restOption => $restValue) {
				$selectedOptions[$restOption] = $restObject->$restOption;
			}
		}

		if (!isset($selectedOptions['selectedPage']) OR $selectedOptions['selectedPage'] == false) {
			$pageSelectOptions = [];
			if(!empty($this->availablePages)) {
				foreach($this->availablePages AS $_page) {
					$pageSelectOptions[] = [
						'value' => $_page['file'],
						'caption' => $_page['name']
					];
				}
			}
			return [
				'title' => $this->getModuleName(),
				'content' => [
					[
						'type' 	=> 'select',
						'name' 	=> 'selectedPage',
						'options' => $pageSelectOptions,
						'label' => $this->languageInstance->getText(get_class($this).":pageSelect")
					],
					[
						'type' => 'buttonGroup',
						'buttons' => [
							[
								'name' => 'newPage',
								'value' => $this->languageInstance->getText(get_class($this).":newPage"),
								'buttonType' => "",
								'onClick' => 'ccCMS_form.queryOption("newPage", "' . $this->languageInstance->getText(get_class($this).":newPageQuery") . '")'
							],
							[
								'name' => 'loadPage',
								'value' => $this->languageInstance->getText(get_class($this).":loadPage"),
								'buttonType' => "primary",
								'onClick' => 'ccCMS_form.sendForm()'
							]
						],
						'align' => 'right',
					]
				]
			];
		} else {

			$selectedPageData = [];
			foreach($this->availablePages AS $availablePage) {
				if ($availablePage['file'] == $selectedOptions['selectedPage']) {
					$selectedPageData = $availablePage;
					$selectedPageData['details'] = $this->_loadPageConfig($selectedOptions['selectedPage']);
				}
			}

			if (!empty($selectedPageData)) {
				
				$formFields = $this->formFields;
				
				foreach($formFields AS $_index => $formField) {
					$formFields[$_index]['label'] = $this->languageInstance->getText(get_class($this).":formField_" . $formField['name']);
					if (isset($selectedPageData['details']->{$formField['name']})) {
						$formFields[$_index]['value'] = $selectedPageData['details']->{$formField['name']};
					}
					$formFields[$_index]['name'] = "details_" . $formField['name'];
				}
				
				return [
					'title' => $this->languageInstance->getText(get_class($this).":loadedPagePrefix") . $selectedPageData['name'],
					'content' => 
						array_merge($formFields, [
							[
								'type' => 'hidden',
								'name' => 'selectedPage',
								'value' => $selectedOptions['selectedPage']
							],
							[
								'type' => 'hidden',
								'name' => 'actionType',
								'value' => 'save'
							],
							[
								'type' => 'buttonGroup',
								'buttons' => [
									[
										'name' => 'close',
										'value' => "Close", //!TODO => LANG
										'buttonType' => "",
										'onClick' => 'ccCMS.openModule("pages")'
									],
									[
										'name' => 'savePage',
										'value' => "Save", //!TODO => LANG,
										'buttonType' => "primary",
										'onClick' => 'ccCMS_form.sendForm()'
									]
								],
								'align' => 'right',
							]
						]),
					'debug' => $selectedOptions
				];
			} else {
				return [
					'title' => $this->languageInstance->getText("misc:errorTitle"),
					'content' => [
						[
							'type' 	=> 'text',
							'value' => $this->languageInstance->getText(get_class($this).":errorSelectedPageNotFound")
						]
					]
				];
			}
		}
	}
	
	private function savePageData($pageFileName, $pageData) 
	{
		return file_put_contents(CMS_CONFIG_PAGES_PATH . "/" . $pageFileName, json_encode($pageData));
	}

	public function processData($restData)
	{
		$restObject = json_decode($restData);
		$returnObject = ['javascript' => ""];
		$actionReturn = false;
		$loadPage = false;
		
		if (isset($restObject->newPage)) {
			//!TODO New Page
		}

		if (isset($restObject->selectedPage)) {
			if (isset($restObject->actionType)) {
				switch($restObject->actionType) {
					case 'delete':
						//!TODO
						$actionReturn = true;
						break;
					case 'save':
					
						$pageDataToSave = [];
						foreach($restObject AS $_name => $_value) {
							if (strpos($_name, "details_") !== false) {
								$pageDataToSave[str_replace("details_", "", $_name)] = $_value;
							}
						}
						if (!empty($pageDataToSave)) {
							$actionReturn = $this->savePageData($restObject->selectedPage, $pageDataToSave);
						}
						
						if ($actionReturn) {
							$returnObject['javascript'] .= 'ccCMS_form.popupMessage("Data saved");'; //!TODO => LANG
						}
						break;
						
				}
			} else {
				$loadPage = $actionReturn = true;
			}
			
			if ($actionReturn && $loadPage) {
				$returnObject['javascript'] .= 'ccCMS.openModule("pages", {"selectedPage": "'.$restObject->selectedPage.'"});';
		
			}
		}

		return $returnObject;
	}
}
