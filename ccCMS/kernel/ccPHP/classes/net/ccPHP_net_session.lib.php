<?php
/**
 * ccPHP_net_session - session handling
 *
 * @version 0.0 - darft
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_net_session extends ccPHP_base
{	
	//speichert ob eine session über cookies oder über parameter funktioniert (0 = parameter, 1 = cookies)
	protected $_session_type = 0;
	
	//initialisiert die session:
    function __construct() 
    {
		setcookie("cookies","yes",time() +"3600");
		if (isset($_COOKIE["cookies"])) {
			$this->_session_type = 1;
		}
		if ($this->_session_type == 0) {
			if (isset($_GET['PHPSESSID'])) {
				session_id($_GET['PHPSESSID']);
			}
			if (isset($_POST['PHPSESSID'])) {
				session_id($_POST['PHPSESSID']);
			}
		} else {
			session_set_cookie_params('1800'); //Lebensdauer in Sec., der Rest wird aus der PHP-INI entnommen falls nicht halt anpassen...
		}
	  	session_start(); 	
    }
    
   	/* liefert die Sessionid zurück */
   	public function getSessId() 
   	{
   		return session_id();
   	}
   	
   	/* liefert - falls das sessionhandling in der aktuellen instanz auf parametern basiert - entsprechend entweder einen <input> für formulare oder einen param für urls zurück 
   		
   		Parameter:
   			type = get / post
   	*/
   	public function getParamSessId($type = "get") 
   	{
   		if ($this->_session_type == 0) {
   			if ($type == "get") {
	   			return "PHPSESSID=".$this->getSessId();
   			} else if ($type == "post") {
   				return '<input type="hidden" name="PHPSESSID" value="'.$this->getSessId().'" />';
   			}
   		}
   	}
   	
   	/* gibt einen Sessionformatierten link zurück falls sessiontyp auf param ist: */
   	public function wellFormatLink($given_link) 
   	{
   		if (!empty($given_link) AND $this->_session_type == 0) {
   			if (strpos($given_link, "?") !== false) {
   				$given_link = $given_link."&PHPSESSID=".$this->getSessId();
   			} else {
   				$given_link = $given_link."?PHPSESSID=".$this->getSessId();
   			}
   			return $given_link;
   		}
   		return $given_link;
   	}
   	
	/* überprüft ob cookies möglich sind, wenn nein gibt es die PHPSESSID für urls zurück */
	public function checkSidType() 
	{
		if ($this->_session_type == 0) {
			return "parameter";
		} else {
			return "cookie";
		}
	}
   	
	/* killt die aktuelle session und erstellt eine neue */
	public function cycleSession() 
	{
		session_destroy();
		session_start();
	}
}