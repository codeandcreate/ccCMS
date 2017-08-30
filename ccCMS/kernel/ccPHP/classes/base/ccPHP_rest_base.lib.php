<?
class ccPHP_rest_base extends ccPHP_base {

	private $encodingType = "application/json";
	private $returnData = null;
	protected $restPieces = [];

	public function __construct($request)
	{
		$this->restPieces = explode("/", $request);
	}

	protected function setReturnContentType($encoding = 'json')
	{
		switch($encoding) {
			case 'json':
				$this->encodingType = "application/json";
				break;
			case 'html':
				$this->encodingType = "application/html";
				break;
			case 'css':
				$this->encodingType = "text/css";
				break;
			case 'js':
				$this->encodingType = "text/javascript";
				break;
			case 'xml':
				$this->encodingType = "text/xml";
				break;
			case 'plain':
				$this->encodingType = "text/plain";
				break;
			case 'octet-stream':
				$this->encodingType = "application/octet-stream";
				break;
			case 'forbidden':
				$this->encodingType = "forbidden";
				break;

		}
	}

	protected function setReturn($returnData)
	{
		$this->returnData = $returnData;
	}

	public function getReturnData()
	{
		$returnData = $this->returnData;
		$contentType = "Content-Type: " . $this->encodingType;

		switch ($this->encodingType) {
			case 'application/json':
				$returnData = json_encode($returnData);
				break;
			case 'forbidden':
				header('HTTP/1.1 403 Forbidden', true, 403);
				$returnData = "Forbidden";
				die();
				break;
		}

		header($contentType);
		return $returnData;
	}
}
