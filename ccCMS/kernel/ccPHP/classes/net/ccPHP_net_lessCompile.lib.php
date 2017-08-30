<?

class ccPHP_net_lessCompile extends ccPHP_base {

  private $lessFormat 	 = "default";
	private $lessUseComments = true;


  function __construct($lessFormat = null, $lessUseComments = null)
  {
		require CCPHP_BASE_PATH . '/3rdparty/lessCompiler/lessc.inc.php';

		if ($lessFormat !== null) {
			$this->lessFormat = $lessFormat;
		}
		if ($lessUseComments !== null) {
			$this->lessUseComments = $lessUseComments;
		}
	}

	private function compileLess($fname = null)
	{
		global $opts;
		$l = new lessc($fname);

		if ($this->lessFormat != "default") $l->setFormatter($format);

		if ($this->lessUseComments) {
			$l->setPreserveComments(true);
		}

		return $l;
	}
	/*

	 $filesForCSSify = [
		[
			'input'		=> "fullpath/source.less",
			'output'	=> "fullpath/compiled.css",
			'preminify' => false
		],...
	]
	 */

	public function compile($filesForCSSify)
	{
		$messages = array();
		$error = false;

		foreach($filesForCSSify AS $config) {
			if (file_exists($config['input'])) {
				try {
					$less = $this->compileLess($config['input'], $this->lessFormat, $this->lessUseComments);

					$out = $less->parse();

					if ($config['preminify']) {
						$out = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $out);
						$out = str_replace(': ', ':', $out);
						$out = str_replace(' {', '{', $out);
						$out = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $out);
					}
					file_put_contents($config['output'], $out);
				} catch (exception $ex) {
					ccPHP_mixed_error::fatal_error("LESS COMPILE ERROR", $config['input']."<br>".$ex->getMessage());
					die();
				}
			} else {
				return false;
			}
		}
		return true;
	}
}
