<?php
/**
 * ccPHP_rest_base - Base restful interface
 *
 * @version 0.1 - Base implementation
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_rest_base extends ccPHP_base 
{
	/*
	 * Default encoding for responses
	 */
	private $encodingType = "application/json";
	/*
	 * cache variable for the response
	 */
	private $returnData = null;
	/*
	 * cache array for the path structure
	 */
	protected $restPieces = [];

	/**
	 * ccPHP_rest_base constructor
	 */
	public function __construct($request)
	{
		$this->restPieces = explode("/", $request);
	}

	/**
	 * sets the encoding type of the return
	 *
	 * @param string $encoding
	 */
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
			default:
				$this->encodingType = $encoding;
				break;

		}
	}

	/**
	 * sets the return data
	 *
	 * @param mixed $returnData
	 */
	protected function setReturn($returnData)
	{
		$this->returnData = $returnData;
	}

	/**
	 * returns the cached data
	 */
	public function getReturnData()
	{
		return $this->returnData;
	}

	/**
	 * returns the cached data
	 */
	public function getEncodingType()
	{
		return $this->encodingType;
	}
	
	public function response()
	{
		$returnData = $this->returnData;
		$contentType = "Content-Type: " . $this->encodingType;

		/*
		 * only json is encoded automaticly
		 */
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
		echo $returnData;
	}
}
