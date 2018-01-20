<?php
/**
 * ccPHP_mixed_phpdotnet - mixed functions found comments on php.net
 *
 * @version 0.1 - initial version
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_mixed_phpdotnet extends ccPHP_base
{
	public static function str_split_unicode($str, $l = 0)
	{
	    if ($l > 0) {
	        $ret = array();
	        $len = mb_strlen($str, "UTF-8");
	        for ($i = 0; $i < $len; $i += $l) {
	            $ret[] = mb_substr($str, $i, $l, "UTF-8");
	        }
	        return $ret;
	    }
	    return preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
	}

	public static function object2array($object)
	{ 
	    $return = NULL; 
	       
	    if(is_array($object)) 
	    { 
	        foreach($object as $key => $value) 
	            $return[$key] = ccPHP_mixed_phpdotnet::object2array($value); 
	    } 
	    else 
	    { 
	    	if (is_object($object)) {
		        $var = get_object_vars($object); 
		           
		        if($var) 
		        { 
		            foreach($var as $key => $value) 
		            	$return[$key] = ($key && !$value) ? NULL : ccPHP_mixed_phpdotnet::object2array($value); 
		        } 
		    }
	        else return $object; 
	    } 
	
	    return $return; 
	} 
}