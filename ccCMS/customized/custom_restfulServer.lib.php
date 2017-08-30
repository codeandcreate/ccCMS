<?
	//just a sample for a custom rest interface

class custom_net_restfulServer extends ccPHP_rest_base {

	public function __construct($request)
	{
		parent::__construct($request);
		// just a sample
		switch($this->restPieces[2]) {
			default:
				$this->setReturn('custom restful server called width "'. $this->restPieces[2] . '".');
		}
	}
}
