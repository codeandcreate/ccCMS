<?php
/**
 * ccPHP_net_markdown - markdown decoding lib
 *
 * @version 0.0 - darft
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_net_markdown extends ccPHP_base {
	
	
	function __construct() 
	{
		require_once CCPHP_BASE_PATH . '/3rdparty/Parsedown/Parsedown.php';
		require_once CCPHP_BASE_PATH . '/3rdparty/ParsedownFilter/ParsedownFilter.php';
		
		$this->mdObj = new ParsedownFilter( 'customFilters', 'ccPHP_net_markdown' );
	}
	
	public function decode($text) 
	{
		return $this->mdObj->text($text);
	}

	public static function customFilters($el) 
	{
		switch( $el[ 'name' ] ){
			case 'a':
				$url = $el[ 'attributes' ][ 'href' ];
		
				/***
					If there is no protocol handler, and the link is not an open protocol address, 
					the links must be relative so we can return as there is nothing to do.
				***/
		
				if( strpos( $url, '://' ) === false )
					if( ( ( $url[ 0 ] == '/' ) && ( $url[ 1 ] != '/' ) ) || ( $url[ 0 ] != '/' ) ){ return; }
				
	
	
				if( strpos( $url, $_SERVER["SERVER_NAME"] ) === false ){
					$el[ 'attributes' ][ 'rel' ] = 'nofollow';
					$el[ 'attributes' ][ 'target' ] = '_blank';
				}
				break;
		return $el;
	}
}