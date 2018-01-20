<?php
/**
 * custom_net_restfulServer - just a sample for a custom rest interface
 *
 * @version 1.0 - example
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class custom_net_restfulServer extends ccPHP_rest_base 
{
	public function __construct($request)
	{
		parent::__construct($request);
		
		switch($this->restPieces[2]) {
			default:
				$this->setReturn('custom restful server called width "'. $this->restPieces[2] . '".');
		}
	}
}
