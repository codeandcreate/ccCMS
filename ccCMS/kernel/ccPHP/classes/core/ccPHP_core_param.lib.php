<?
class ccPHP_core_param extends ccPHP_base {

	protected $_PARAM = array();
	
	function __construct($breakpoint, $domainext = ".de", $protocoll = 'http', $port = "")
	{
		if ($_SERVER['SERVER_NAME'] == "localhost") {
			$_request_data_break_point = (strpos($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ,$breakpoint)+strlen($breakpoint));
		} else {
			$_request_data_break_point = (strpos($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'] ,$breakpoint)+strlen($breakpoint) + strlen($domainext));
		}
		
		//$_request_data_break_point = $_request_data_break_point - 5;
	
		$request_data = substr($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], $_request_data_break_point);
		
		$this->_PARAM = $this->getParamArray($request_data, "/");
		
		//aktuelle URL noch zur verfügung stellen:
		$this->_PARAM['SERVER_ROOT_URL'] = $protocoll."://".substr($_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'], 0, $_request_data_break_point).$port."/";
	}
	
	protected function getParamArray($string, $limiter)
	{
	
		$request_data = explode($limiter, $string);
	
		$tmp_data_name = "";
		foreach($request_data AS $request_data_child) {
		
			if (!empty($request_data_child)) {
				if (strpos($request_data_child, ",")) {
					$var_set = explode(",", $request_data_child);
					$_PARAM[$var_set[0]] = $var_set[1];
				} else {
					$_PARAM[$request_data_child] = true;
				}
			}
		
		}
		//letzten param abfangen:
		if (!empty($tmp_data_name)) {
			$_PARAM[$tmp_data_name] = "";
		}
		return $_PARAM;
	}
	
	/* wenn $is_index auf true gesetzt ist muss param_name einen index (also int) des arrays beinhalten. Damit wird dann (sofern vorhanden) der keyname ausgegeben */
	public function getParam($_param_name = "", $is_index = "false")
	{
		if (empty($_param_name) AND $is_index == "false") {
			return $this->_PARAM;
		} else {
			if ($is_index == "true") {
				$_tmp = array_keys($this->_PARAM);
				if (isset($_tmp[$_param_name])) {
					return $_tmp[$_param_name];
				}
			} else if (isset($this->_PARAM[$_param_name])) {
				return $this->_PARAM[$_param_name];
			}
		}
		return false;
	}
}