<?php
/**
 * ccPHP_core_settings - settings management for a page / ccCMS
 *
 * @version 0.0 - darft
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_core_settings extends ccPHP_base
{	
	protected $_website_data = [];
	
    function __construct($title, $keywords, $description, $copyright, $robots, $revisitafter, $rating, $publisher, $language, $js_files, $css_files) 
    {
    	$this->_website_data['title'] = $title;
    	$this->_website_data['keywords'] = $keywords;
    	$this->_website_data['description'] = $description;
    	$this->_website_data['copyright'] = $copyright;
    	$this->_website_data['robots'] = $robots;
    	$this->_website_data['revisitafter'] = $revisitafter;
    	$this->_website_data['rating'] = $rating;
    	$this->_website_data['publisher'] = $publisher;
    	$this->_website_data['language'] = $language;
    	$this->_website_data['js'] = $js_files;
    	$this->_website_data['css'] = $css_files;
    }
    
    public function getParam($paramname) 
    {
    	if (isset($this->_website_data[$paramname])) {
	    	return $this->_website_data[$paramname];
	    }
	    return false;
    }
    
    public function setParam($paramname, $paramvalue) 
    {
    	if ($this->_website_data[$paramname]) {
    		$this->_website_data[$paramname] = $paramvalue;
    		return true;
    	}
    	return false;
    }
    
    public function getPageTitle($additional_title) 
    {
    	if (!empty($additional_title)) {
    		return $this->_website_data['title']." - ".$additional_title;
    	} 
    	return $this->_website_data['title'];
    }
}