<?php
/**
 * ccPHP_net_restfulServer - restful server
 *
 * @version 0.0 - darft
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_net_restfulServer extends ccPHP_rest_base
{
	private $cmsInstance = null;

	public function __construct($request, $cmsInstance)
	{
		parent::__construct($request);

		$this->cmsInstance = $cmsInstance;

		switch($this->restPieces[1]) {
			case 'compileLess':
				$this->handleCompileLess($this->restPieces);
				break;
			case 'backend':
				if (CMS_MODE === 'backend' && isset($_SERVER['PHP_AUTH_USER'])) {
					$restData = (isset($_POST['data']) && !empty($_POST['data'])) ? $_POST['data'] : [];
					switch($this->restPieces[2]) {
						case 'module':
							if (in_array($this->restPieces[3], CMS_MODULES)) {
								$bmInstance = $this->cmsInstance->backendModuleInstances[$this->restPieces[3]];
								if (isset($_POST['action'])) {
									switch($_POST['action']) {
										case 'form':
											$this->setReturn($bmInstance->getForm($restData));
											break;
										case 'data':
											$this->setReturn($bmInstance->processData($restData));
											break;
									}
								} else {
									$this->setReturn(null);
								}
							} else {
								$this->setReturn(null);
							}
							break;
						default:
							$this->setReturn(null);
					}
				} else {
					$this->setReturnContentType("forbidden");
				}
				break;
			case 'js':
			case 'css':
				if (isset($this->restPieces[2]) && file_exists(CMS_BASE_PATH . "/kernel/" . $this->restPieces[1] . "/" . $this->restPieces[2])) {
					$this->setReturnContentType($this->restPieces[1]);
					$this->setReturn(file_get_contents(CMS_BASE_PATH . "/kernel/" . $this->restPieces[1] . "/" . $this->restPieces[2]));
				} else {
					$this->setReturn(null);
				}
				break;
			case 'custom':
				if (class_exists('custom_net_restfulServer')) {
					$customRestfulInstance = new custom_net_restfulServer($request);
					$this->setReturn($customRestfulInstance->getReturnData());
				} else {
					$this->setReturn(null);
				}
				break;
			default:
				$this->setReturn(null);
		}
	}

	private function handleCompileLess()
	{
		if (isset($this->restPieces[2])) {

			$restFiles = [];
			$this->setReturn(false);

			if ($this->restPieces[2] == 'cms') {
				$restFiles = [
					[
						'input'		=> CMS_BASE_PATH . "/kernel/less/ccCMS.less",
						'output'	=> CMS_BASE_PATH . "/css/ccCMS.css",
						'preminify' => true
					]
				];
			} else if ($this->restPieces[2] == 'site') {
				$restFiles = [
					[
						'input'		=> CMS_BASE_PATH . "/themes/" . SITE_THEME . "/less/site.less",
						'output'	=> CMS_BASE_PATH . "/../css/site.css",
						'preminify' => true
					]
				];
			}
			if (!empty($restFiles)) {
				$lessInstance = new ccPHP_net_lessCompile();
				$this->setReturn($lessInstance->compile($restFiles));
			}
		} else {
			$this->setReturn(false);
		}
	}
}
