<?php
/**
 * ccPHP_net_bbcode - bbcode decoding lib
 *
 * @version 0.1 - darft
 * @deprecated
 * @author Matthias Weiß <info@codeandcreate.de>
 */
class ccPHP_net_bbcode extends ccPHP_base
{	
	public $bbobj;
	
	function __construct() 
	{
		require_once CCPHP_BASE_PATH . '/3rdparty/stringparser_bbcode.class.php';
	
		$this->bbobj = new StringParser_BBCode();
		
		$this->bbobj->addFilter (STRINGPARSER_FILTER_PRE, 'ccPHP_net_bbcode::convertlinebreaks');

		$this->bbobj->addParser (array ('block', 'inline', 'link', 'listitem'), 'ccPHP_net_bbcode::htmlspecialchars');
		$this->bbobj->addParser (array ('block', 'inline', 'link', 'listitem'), 'ccPHP_net_bbcode::nl2br');
		$this->bbobj->addParser ('list', 'ccPHP_net_bbcode::bbcode_stripcontents');
		
		$this->bbobj->addCode ('h2', 'simple_replace', null, array ('start_tag' => '<h2>', 'end_tag' => '</h2>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('h3', 'simple_replace', null, array ('start_tag' => '<h3>', 'end_tag' => '</h3>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('h4', 'simple_replace', null, array ('start_tag' => '<h4>', 'end_tag' => '</h4>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('h5', 'simple_replace', null, array ('start_tag' => '<h5>', 'end_tag' => '</h5>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		
		$this->bbobj->addCode ('b', 'simple_replace', null, array ('start_tag' => '<b>', 'end_tag' => '</b>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('u', 'simple_replace', null, array ('start_tag' => '<u>', 'end_tag' => '</u>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('i', 'simple_replace', null, array ('start_tag' => '<i>', 'end_tag' => '</i>'),
		                  'inline', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('url', 'usecontent?', 'ccPHP_net_bbcode::do_bbcode_url', array ('usecontent_param' => 'default'),
		                  'link', array ('listitem', 'block', 'inline'), array ('link'));
		
		$this->bbobj->addCode ('img', 'usecontent', 'ccPHP_net_bbcode::do_bbcode_img', array (),
		                  'image', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->addCode ('bild', 'usecontent', 'ccPHP_net_bbcode::do_bbcode_img', array (),
		                  'image', array ('listitem', 'block', 'inline', 'link'), array ());
		$this->bbobj->setOccurrenceType ('img', 'image');
		$this->bbobj->setOccurrenceType ('bild', 'image');
		$this->bbobj->setMaxOccurrences ('image', 2);
		$this->bbobj->addCode ('list', 'simple_replace', null, array ('start_tag' => '<ul>', 'end_tag' => '</ul>'),
		                  'list', array ('block', 'listitem'), array ());
		$this->bbobj->addCode ('*', 'simple_replace', null, array ('start_tag' => '<li>', 'end_tag' => '</li>'),
		                  'listitem', array ('list'), array ());
						  
		$this->bbobj->setCodeFlag ('*', 'closetag', BBCODE_CLOSETAG_OPTIONAL);
		$this->bbobj->setCodeFlag ('*', 'paragraphs', true);
		$this->bbobj->setCodeFlag ('list', 'paragraph_type', BBCODE_PARAGRAPH_BLOCK_ELEMENT);
		$this->bbobj->setCodeFlag ('list', 'opentag.before.newline', BBCODE_NEWLINE_DROP);
		$this->bbobj->setCodeFlag ('list', 'closetag.before.newline', BBCODE_NEWLINE_DROP);
		$this->bbobj->setRootParagraphHandling (false);
	}
	
	// ZeilenumbrŸche verschiedener Betriebsysteme vereinheitlichen
	public function convertlinebreaks ($text) 
	{
    	return preg_replace ("/\015\012|\015|\012/", "<br />", $text);
	}
	
	// Alles bis auf Neuezeile-Zeichen entfernen
	public	function bbcode_stripcontents ($text) 
	{
   		return preg_replace ("/[^\n]/", '', $text);
	}
	
	public function do_bbcode_url ($action, $attributes, $content, $params, $node_object) 
	{
	    if (!isset ($attributes['default'])) {
	        $url = $content;
	        $text = htmlspecialchars ($content);
	    } else {
	        $url = $attributes['default'];
	        $text = $content;
	    }
	    if ($action == 'validate') {
	        if (substr ($url, 0, 5) == 'data:' || substr ($url, 0, 5) == 'file:'
	          || substr ($url, 0, 11) == 'javascript:' || substr ($url, 0, 4) == 'jar:') {
	            return false;
	        }
	        return true;
	    }
	    return '<a href="'.htmlspecialchars ($url).'">'.$text.'</a>';
	}

	// Funktion zum Einbinden von Bildern
	public function do_bbcode_img ($action, $attributes, $content, $params, $node_object) 
	{
	    if ($action == 'validate') {
	        if (substr ($content, 0, 5) == 'data:' || substr ($content, 0, 5) == 'file:'
	          || substr ($content, 0, 11) == 'javascript:' || substr ($content, 0, 4) == 'jar:') {
	            return false;
	        }
	        return true;
	    }
	    return '<img src="'.htmlspecialchars($content).'" alt="">';
	}
	
	public function decode($text) 
	{
		return $this->bbobj->parse ($text);
	}
}