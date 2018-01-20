<?php
/**
 * ccPHP_mixed_language - language file handling
 *
 * @version 0.0 - darf
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_mixed_language extends ccPHP_base {
	
	private $languageData = null;
	
	private static $instance = null;
	
    private function __construct($languageFile) 
    {
    	if (file_exists($languageFile)) {
	    	try {
		    	$this->languageData = json_decode(file_get_contents($languageFile), true);
		    	if (empty($this->languageData)) {	
					ccPHP_mixed_error::fatal_error(get_class($this), 'Error in language file. no data found');
					die();
		    	}
		    } catch (exception $ex) {
				ccPHP_mixed_error::fatal_error(get_class($this), 'Could not load language file (' . $languageFile . ' / ' . print_r($ex,true) . ')');
				die();
			}
	    } else {
	    	ccPHP_mixed_error::fatal_error(get_class($this), 'Language file not found (' . $languageFile . ')');
			die();
    	}
    }
    
    public static function getInstance($languageFile = null) 
    {
		if ($languageFile !== null) {
			self::$instance = new ccPHP_mixed_language($languageFile);
			return self::$instance;
		} else if (self::$instance !== null) {
			return self::$instance;
		}
		
    	ccPHP_mixed_error::fatal_error(get_class($this), 'no instance initialized');
		die();
    }
    
    public function getText($languageString) 
    {
    	if (isset($this->languageData[$languageString])) {
	    	return $this->languageData[$languageString];
	    }
	    return '';
    }
}