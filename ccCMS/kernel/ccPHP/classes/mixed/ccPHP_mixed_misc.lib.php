<?
/*
	ccPHP_misclib
	
	Allgemeines Library für Funktionen die sonst nirgends Platz haben
*/

class ccPHP_mixed_misc extends ccPHP_base {
    
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
	
	public static function sqliteNOW()
	{
		return date("Y-m-d H:i:s");
	}
	
	public static function copyProtectText($text)
	{
		$cpccharakterset = "abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ!'ß$%&/()=?_:;#+-*";
		$textArray = phpdotnet::str_split_unicode($text);
		foreach($textArray AS &$zeichen) {
			$zeichen .= '<span class="cpc">' . $cpccharakterset[rand(0,strlen($cpccharakterset)-1)] . '</span>';
		}
		return implode("", $textArray);
	}
}