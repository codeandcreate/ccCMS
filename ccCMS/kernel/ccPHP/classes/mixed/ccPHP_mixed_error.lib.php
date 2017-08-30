<?
class ccPHP_mixed_error extends ccPHP_base {
    
    public static function fatal_error($error_title = "", $error_text) 
    {
    	echo '<div style="border:1px dotted #ff0000;background-color:#550;padding:10px;color:#f00;">';
    	if (!empty($error_title)) {
    		echo '<b>'.$error_title."</b><br/>";
    	}
    	echo $error_text."</div>";
    	return true;
    }
}