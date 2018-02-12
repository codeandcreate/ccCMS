<?php
/**
 * ccPHP_mixed_misc - misc functions that don't fit anywhere alse
 *
 * @version 0.1 - initial version
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_mixed_misc extends ccPHP_base
{
    /**
	 * Checks the structure of an email and tries to connect to the host to verify
	 *
	 * @param string $email
	 * @return bool
	 */
    public static function checkEmail($email)
    {
	   if(eregi("^[a-zA-Z0-9_]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$]", $email)) {
	      return FALSE;
	   }
	
	   list($Username, $Domain) = split("@",$email);
	
	   if(getmxrr($Domain, $MXHost))  {
	      return TRUE;
	   } else {
	      if(fsockopen($Domain, 25, $errno, $errstr, 30)) {
	         return TRUE; 
	      } else  {
	         return FALSE; 
	      }
	   }
	}

    /**
	 * Returns a date string for sqlite databases
	 *
	 * @return string
	 */
	public static function sqliteNOW()
	{
		return date("Y-m-d H:i:s");
	}

    /**
	 * Mixes in some random text to copy protect text (needs a css class to hides the mixed in blocks)
	 *
	 * @param string $text
	 * @return string
	 */
	public static function copyProtectText($text)
	{
		$cpccharakterset = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!'ß$%&/()=?_:;#+-*";
		$textArray = ccPHP_mixed_phpdotnet::str_split_unicode($text);
		foreach($textArray AS &$zeichen) {
			$zeichen .= '<span class="cpc">' . $cpccharakterset[rand(0,strlen($cpccharakterset)-1)] . '</span>';
		}
		return implode("", $textArray);
	}
	
	/**
	 * Returns the workmode; web-server or cli-server
	 * 
	 * @return string	"web-server" | "cli-server"
	 */
	public static function phpWorkMode()
	{
		$workmode = "web-server";
		if (php_sapi_name() == 'cli-server') {
			$workmode = "cli-server";
		}
		
		return $workmode;
	}
}