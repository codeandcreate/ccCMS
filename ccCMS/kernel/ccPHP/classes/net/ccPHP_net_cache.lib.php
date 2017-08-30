<?
class ccPHP_net_cache extends ccPHP_base {

	private $cacheParams = [];
	
    function __construct($cachePath = false, $cacheTime = false) 
    {	
    	$this->cacheParams = array(
			'cachePath' => CCPHP_BASE_PATH . "/cache",
			'cacheTime' => 86400
		);
    
    	if (is_dir($cachePath)) {
	    	$this->cacheParams['cachePath'] = $cachePath;
    	}
    	if (is_numeric($cacheTime)) {
	    	$this->cacheParams['cacheTime'] = $cacheTime;
    	}
    }
    
    private function _getCacheKey($cacheId) 
    {
	    return md5($cacheId);
    }
    
    public function setCache($cacheId, $cacheData, $ownCacheTime = false)
    {	
    	if (!empty($cacheId)) {
    	
	    	$cacheKey = $this->_getCacheKey($cacheId);
	    	$cacheFile = $this->cacheParams['cachePath'] . "/" . $cacheKey . ".xml";
	    	
	    	/* alten Cache löschen */
	    	if (file_exists($cacheFile)) {
		    	unlink($cacheFile);
	    	}
	    	
	    	/* Ohne Cache-Daten; nur Löschen */
	    	if (empty($cacheData)) {
	    		return true;
	    	}
	    	
	    	/* Cache Zeit gobal wenn nicht übergeben */
	    	if (!is_numeric($ownCacheTime)) {
		    	$ownCacheTime = $this->cacheParams['cacheTime'];
	    	}
	    	$ownCacheTime = time() + $ownCacheTime;
	    	
	    	/* Art der Daten */
	    	$typeOfCacheData = "String";
	    	if (is_array($cacheData)) {
		    	$cacheData = serialize($cacheData);
		    	$typeOfCacheData = "Array";
	    	}
	    	if (is_object($cacheData)) {
	    		$cacheData = serialize(phpdotnet::object2array($cacheData));
		    	$typeOfCacheData = "Array";
	    	}
	    	
	    	/* cacheFile erstellen; keine Funktionen, nur Strig; Schneller */
	    	$cacheFileData = 
	    	"<ccPHP_cacheFile>".
	    		"<validTill>" . $ownCacheTime . "</validTill>" .
	    		"<dataType>" . $typeOfCacheData . "</dataType>" .
	    		"<data><![CDATA[" . $cacheData . "]]></data>" .
	    	"</ccPHP_cacheFile>";
	    	
	    	$fp = fopen($cacheFile, "w");
	    	fputs ($fp, $cacheFileData);
	        fclose ($fp);
	        
	        return true;
	        
	    }
    	
    	return false;
    }
    
    public function getCache($cacheId) 
    {	
    	if (!empty($cacheId)) {
    	
	    	$cacheKey = $this->_getCacheKey($cacheId);
	    	$cacheFile = $this->cacheParams['cachePath'] . "/" . $cacheKey . ".xml";
	    	
	    	if (file_exists($cacheFile)) {
		    	$cacheFileData = new DOMDocument();
		    	$cacheFileData->load($cacheFile);
		    	
		    	$validTill = $cacheFileData->getElementsByTagName('validTill')->item(0)->nodeValue;
		    	
		    	if ($validTill >= time()) {
			    	$dataType = $cacheFileData->getElementsByTagName('dataType')->item(0)->nodeValue;
			    	$cacheData = $cacheFileData->getElementsByTagName('data')->item(0)->nodeValue;
			    	
			    	if ($dataType == "Array") {
				    	$cacheData = unserialize($cacheData);
			    	}
			    	
			    	return $cacheData;
		    	}
	    	}
	    }
    
    	return false;
    }
}