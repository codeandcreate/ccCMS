<?

class ccPHP_net_simpleBlog extends ccPHP_base {
	
	protected $_entries_path = "";
	protected $_entries_per_page = "";
	protected $_list_entries = array();
	protected $_list_entries_by_date = array();
	
	private $cacheInstance = false;
	
	//initialisiert die seite:
    function __construct($entries_path, $entries_per_page = 5)
    {
    	$this->_entries_path = $entries_path;
    	$this->_entries_per_page = $entries_per_page;
    	
    	$this->cacheInstance = new ccPHP_cache();
    	
    	
    	//checking:
    	if (!$this->_list_files()) {
    		ccPHP_error::fatal_error(__FILE__, "CONSTRUCT: no entries.");
   			die();
    	}
    }
    
    protected function _list_files($noCache = false)
    {
    	$cachedData = $cachedDataEntriesByDate = false;
    	if (!$noCache) {
			$cachedData = $this->cacheInstance->getCache("blogList" . $this->_entries_path);
			$cachedDataEntriesByDate = $this->cacheInstance->getCache("blogListEntriesByDate" . $this->_entries_path);
		}
    
		if (!$cachedData OR !$cachedDataEntriesByDate) {
			
    		$this->_list_entries = array();
    		$d = dir($this->_entries_path);
    		
    		$filesToProcess = array();
    		while (false !== ($entry = $d->read())) {
				if (strpos($entry, ".bhtml") !== false){
					$filesToProcess[] = $entry;
				}
			}
			$d->close();
    		
    		if (!empty($filesToProcess)) {
				sort($filesToProcess);
				
				foreach($filesToProcess AS $entry) {
					$entryid = substr($entry,0,8);
					if (file_exists($this->_entries_path."/".$entryid."_meta.xml")) {
						$entry_data['meta'] = phpdotnet::object2array(simplexml_load_file($this->_entries_path."/".$entryid."_meta.xml"));
					}
					$entry_data['meta']['date'] = date("d.m.Y", strtotime($entryid));
					$entry_data['meta']['timestamp'] = strtotime($entryid);
					$entry_data['meta']['id'] = $entryid;
					$entry_data['filename'] = $entry;
					
					$this->_list_entries_by_date[date('Y', $entry_data['meta']['timestamp'])][date('m', $entry_data['meta']['timestamp'])][date('d', $entry_data['meta']['timestamp'])][] = $entryid;
					
					$this->_list_entries['files'][$entryid] = $entry_data;
				}
			}
			
			if (!isset($this->_list_entries['files'])) {
				return false;
			}
    		
 			if (!$noCache) {
 				$this->cacheInstance->setCache("blogList" . $this->_entries_path, $this->_list_entries);
 				$this->cacheInstance->setCache("blogListEntriesByDate" . $this->_entries_path, $this->_list_entries_by_date);
 			}
		} else {
			$this->_list_entries = $cachedData;
			$this->_list_entries_by_date = $cachedDataEntriesByDate;
		}
    	
		return true;
    }
    
    public function getEntriesByDate() 
    {
	    return $this->_list_entries_by_date;
    }
    
    public function getEntries($page = "") 
    {
    	$return_values = array();
    	if (!empty($page)) {
    		$start_id = ($page-1) * $_entries_per_page;
    		$end_id = ($page * $_entries_per_page)-1;
    		for($i = $start_id; $i<= $end_id; $i++) {
    			if (isset($this->_list_entries['files'][$i])) {
    				$return_values[] = $this->_list_entries['files'][$i];
    			}
    		}
    	} else {
    		$return_values = $this->_list_entries['files'];
    	}
    	if (empty($return_values)) {
    		$return_values = false;
    	}
    	return $return_values;
    }
    
    public function getLastEntry() 
    {
	    return end($this->_list_entries['files']);
    }
	
	public function getPreviousEntry($id) 
	{
		$entries = $this->getEntries();
		$lastPreviousEntry = false;
		foreach($entries AS $entryID => $entryData) {
			if ($id == $entryID AND $lastPreviousEntry) {
				return $lastPreviousEntry;
			}
			$lastPreviousEntry = $entryID;
		}
		return false;
	}
	
	public function getNextEntry($id) 
	{
		$entries = $this->getEntries();
		$giveNextEntry = false;
		foreach($entries AS $entryID => $entryData) {
			if ($giveNextEntry) {
				return $entryID;
			}
			if ($id == $entryID) {
				$giveNextEntry = true;
			}
		}
		return false;
	}
    
    /* Achtung: es muss ein entryarray übergeben werden wie es von getEntries erzeugt wird.*/
    public function getSelectedEntry(&$entry_array, $optional_image_path = "", $noCache = false)
    {
    	if (!is_array($entry_array)) {
    		ccPHP_error::fatal_error(__FILE__, "getSelectedEntry: no entry array given.");
   			die();
    	}
    	
    	
    	$cachedData = false;
    	if (!$noCache) {
			$cachedData = $this->cacheInstance->getCache("blogEntry" . $entry_array['meta']['id'] . $entry_array['meta']['timestamp']);
		}
		
		if (!$cachedData) {
    	
	    	if (!file_exists($this->_entries_path."/".$entry_array['filename'])) {
   	 			ccPHP_error::fatal_error(__FILE__, "getSelectedEntry: can't find datafile (".$this->_entries_path."/".$entry_array['filename'].").");
   	 			die();
   	 			}
   	 			//haupttext:
    	
   	 			$bbcode_obj = new ccPHP_bbcode($optional_image_path);
   	 			$bbcode_data = file_get_contents($this->_entries_path."/".$entry_array['filename']);
   	 			$bbcode_data = str_replace("\n", "<br />", $bbcode_data);
   	 			$cachedData = $bbcode_obj->decode($bbcode_data);
   	 			
   	 			if (!$noCache) {
   	 				$this->cacheInstance->setCache("blogEntry" . $entry_array['meta']['id'] . $entry_array['meta']['timestamp'], $cachedData);
   	 			}
   	 			
   	 	}
		
		$entry_array['data'] = $cachedData;
		
		return $entry_array;
    }
}

?>