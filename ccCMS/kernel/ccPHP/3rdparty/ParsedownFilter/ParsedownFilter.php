<?php
	#
	#
	# ParsedownFilter
	#
	# An extension for Parsedown ( http://parsedown.org )
	#
	# Written by Christopher Andrews ( http://arduino.land/ )
	# Extended by Matthias Weiß ( info@codeandcreate.de )
	# Released under GPL & MIT licenses.
	#
	
	

	class ParsedownFilter extends Parsedown
	{
	
		private $tagCallback;
		private $tagCallbackClassInstance;
	
		function __construct( $tag_Callback , $classInstance = false)
		{
			if ($classInstance !== false) {
				$this->tagCallbackClassInstance = $classInstance;
			}
			
			$this->tagCallback = $tag_Callback;
			
		}
		
		
		protected function element(array $Element)
		{
			
			if( isset( $this->tagCallback ) ){
			
				if( is_array( $Element ) ){
				
					if( is_string( $Element[ 'name' ] ) ){
					
						$strf = $this->tagCallback;
						if ($this->tagCallbackClassInstance !== false) {
							$result = call_user_func([$this->tagCallbackClassInstance, $strf], $Element);
						} else {
							$result = $strf( $Element );
						}
						
						if( $result === false ){
							//Remove tag.
						}
					}
				}
			}
			//Return result using modified values.
			return parent::element( $result );
		}
	};
?>