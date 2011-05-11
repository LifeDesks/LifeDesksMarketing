<?php

class RSSGenerator {
	
	private $_version = "1.0";
	private $_namespaces = array('xmlns:atom="http://www.w3.org/2005/Atom"');
	private $_atomLink = "";
	private $_title = "My RSS Feed";
	private $_description = "This is my description";
	private $_link = "http://example.com";
	private $_data = array();
	private $_items = array();
	private $_sortkey = "";
	
	public function _construct() {
	}
	
	public function setVersion($version) {
		$this->_version = $version; 
	}
	
	public function addNameSpace($namespace) {
		$this->_namespaces[] = $namespace;
	}
	
	public function setAtomLink($link) {
		$this->_atomLink = $link;
	}
	
	public function setTitle($title) {
		$this->_title = $title;
	}
	
	public function setDescription($description) {
		$this->_description = $description;
	}
	
	public function setLink($link) {
		$this->_link = $link;
	}
	
	public function addItem($data = array()) {
		$this->_items[] = array(
			"title" => htmlspecialchars($data['title'], ENT_QUOTES, 'UTF-8'),
			"link" => $data['link'],
			"pubDate" => (is_int($data['pubDate'])) ? gmdate('D, d M Y H:i:s O', $data['pubDate']) : $data['pubDate'],
			"guid" => $data['guid'],
			"description" => "<![CDATA[".$data['description']."]]>",
			"media:thumbnail" => (isset($data['media:thumbnail'])) ? $data['media:thumbnail'] : "",
			"media:content" => (isset($data['media:content'])) ? $data['media:content'] : ""
		);
	}
	
	public function createRSS() {
		$this->_output  = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>" . "\n";
		$this->_output .= "<rss version=\"$this->_version\" " . implode(" ", $this->_namespaces) . ">" . "\n";
		$this->_output .= "<channel>" . "\n";
		$this->_output .= "<atom:link href=\"$this->_atomLink\" rel=\"self\" type=\"application/rss+xml\"></atom:link>" . "\n";
		$this->_output .= "<link>$this->_link</link>" . "\n";
		$this->_output .= "<title>".htmlspecialchars($this->_title, ENT_QUOTES, 'UTF-8')."</title>" . "\n";
		$this->_output .= "<description>$this->_description</description>" . "\n";
		$this->_output .= "<language>en-us</language>" . "\n";
		
		foreach($this->_items as $items) {
			$this->_output .= "<item>" . "\n";
			foreach($items as $key => $item) {
				if(($key == "media:thumbnail" || $key == "media:content") && is_array($item)) {
					$this->_output .= "<$key " . $this->makeAttributes($item) . "></$key>" . "\n";
				}
				else {
					$this->_output .= "<$key>$item</$key>" . "\n";
				}
				
			}
			$this->_output .= "</item>" . "\n";
		}
		
		
		$this->_output .= "</channel>" . "\n";
		$this->_output .= "</rss>";
		
		return $this->_output; 
	}
	
	function sort($array, $key_to_sort, $type_of_sort = ""){
        $this->_sortkey = $key_to_sort;
       
        if ($type_of_sort == "desc") {
            uasort($array, array($this, "reverse_compare"));
        }
        else {
            uasort($array, array($this, "compare"));
        }
           
        return $array;
    }
   
    //for ascending order
    function compare($x, $y){
        if ( $x[$this->_sortkey] == $y[$this->_sortkey] )
            return 0;
        else if ( $x[$this->_sortkey] < $y[$this->_sortkey] )
            return -1;
        else
            return 1;
    }
   
    //for descending order
    function reverse_compare($x, $y){
        if ( $x[$this->_sortkey] == $y[$this->_sortkey] )
            return 0;
        else if ( $x[$this->_sortkey] > $y[$this->_sortkey] )
            return -1;
        else
            return 1;
    }

    function makeAttributes(&$item) {
		array_walk($item, array($this, 'flatten'));
		return implode(" ", $item);
    }

    private function flatten(&$value, &$key) {
		$value = $key . "=\"" . $value . "\"";
	}
	
}
?>