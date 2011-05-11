<?php
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../conf.inc.php');
require_once "functions.php";

class SolrAPI
{
    private $server;
    private $core;
    private $primary_key;
    private $schema_object;
    private $file_delimiter;
    private $multi_value_delimiter;
    private $csv_path;
    private $action_url;
    private $context_timeout;

    public function __construct($s = SOLR_SERVER, $core = '', $d = SOLR_FILE_DELIMITER, $mv = SOLR_MULTI_VALUE_DELIMETER, $timeout = SOLR_TIMEOUT)
    {
        $this->server = trim($s);
        if(!preg_match("/\/$/", $this->server)) $this->server .= "/";
        $this->core = $core;
        if(preg_match("/^(.*)\/$/", $this->core, $arr)) $this->core = $arr[1];
        $this->file_delimiter = $d;
        $this->multi_value_delimiter = $mv;
        $this->csv_path = CSVPATH;
        $this->action_url = $this->server . $this->core;
        if(preg_match("/^(.*)\/$/", $this->action_url, $arr)) $this->action_url = $arr[1];

        $this->context_timeout = stream_context_create(
          array('http' => array('timeout' => $timeout))
        );

        $this->load_schema();
    }

    function __destruct()
    {
        @unlink($this->csv_path);
    }

    public static function ping($s = SOLR_SERVER, $c = '', $timeout = SOLR_TIMEOUT)
    {
        $server = trim($s);
        if(!preg_match("/\/$/", $server)) $server .= "/";
        $core = $c;
        if(preg_match("/^(.*)\/$/", $core, $arr)) $core = $arr[1];
        $action_url = $server . $core;
        if(preg_match("/^(.*)\/$/", $action_url, $arr)) $action_url = $arr[1];
        $stream = stream_context_create(array('html' => array('method' => 'GET', 'timeout' => $timeout)));
        $schema = @file_get_contents($action_url . "/admin/file/?file=schema.xml", 0, $stream);
        if($schema) return true;
        return false;
    }

    private function load_schema()
    {
        // load schema XML
        $response = simplexml_load_string(@file_get_contents($this->action_url . "/admin/file/?file=schema.xml", 0, $this->context_timeout));

        if($response) {
            // set primary key field name
	        $this->primary_key = (string) $response->uniqueKey;

	        // create empty object that maps to each field name; will be array if multivalued
	        $this->schema_object = new stdClass();
	        foreach($response->fields->field as $field)
	        {
	            $field_name = (string) $field['name'];
	            $multi_value = (string) @$field['multiValued'];

	            if($multi_value) $this->schema_object->$field_name = array();
	            else $this->schema_object->$field_name = '';
	        }
        }
    }

    public function query($query, $offset = 0, $limit = 10, $params = array())
    {
        if (!is_array($params))
        {
            $params = array();
        }
        $params['start'] = $offset;
        $params['rows'] = $limit;
        $params['wt'] = 'json';
        $queryString = http_build_query($params, null, '&');
        $queryString = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $queryString);
        $json = json_decode(@file_get_contents($this->action_url."/select/?q={!lucene}".str_replace(" ", "%20", $query)."&".$queryString, 0, $this->context_timeout));
        return ($json) ? $json : "";
    }

    public function get_results($query)
    {
        $objects = array();
        $response = $this->query($query);
        return $response->docs;
    }

    public function commit()
    {
        exec("curl ". $this->action_url ."/update -F stream.url=".BASE_URL."/conf/solr/commit.xml");
    }

    public function optimize()
    {
        exec("curl ". $this->action_url ."/update -F stream.url=".BASE_URL."/conf/solr/optimize.xml");
    }

    public function delete_all_documents()
    {
        exec("curl ". $this->action_url ."/update -F stream.url=".BASE_URL."/conf/solr/delete.xml");
        $this->commit();
        $this->optimize();
    }

    public function swap($from_core, $to_core)
    {
        exec("curl ". $this->server ."admin/cores -F action=SWAP -F core=$from_core -F other=$to_core");
    }

    public function delete($query)
    {
        @unlink(DOC_ROOT . $this->csv_path);
        $OUT = fopen(DOC_ROOT . $this->csv_path, "w+");
        fwrite($OUT, "<delete><query>$query</query></delete>");
        fclose($OUT);

        exec("curl ". $this->action_url ."/update -F stream.url=".BASE_URL."$this->csv_path");
        $this->commit();
    }

    public function send_attributes($objects)
    {
        @unlink($this->csv_path);
        $OUT = fopen($this->csv_path, "w+");

        $fields = array_keys(get_object_vars($this->schema_object));
        fwrite($OUT, implode($this->file_delimiter, $fields) . "\n");

        foreach($objects as $primary_key => $attributes)
        {
            $this_attr = array();
            foreach($fields as $attr)
              $this_attr[$attr] = $attributes[$attr];
            fwrite($OUT, implode($this->file_delimiter, $this_attr) . "\n");
        }
        fclose($OUT);

        $curl = "curl ". $this->action_url ."/update/csv -F overwrite=true -F separator='". $this->file_delimiter ."'";
        $curl .= " -F header=false -F fieldnames=".implode(",", $fields);
        $curl .= " -F stream.url=".BASE_URL."/files/solr_csv.text -F stream.contentType=text/plain;charset=utf-8";

        echo "calling: $curl\n";
        exec($curl);
        $this->commit();
    }

    public static function text_filter($text, $convert_to_ascii = true)
    {
        if(!Functions::is_utf8($text)) return "";
        $text = str_replace(";", " ", $text);
        $text = str_replace("×", " ", $text);
        $text = str_replace("\"", " ", $text);
        $text = str_replace("'", " ", $text);
        $text = str_replace("|", "->", $text);
        $text = str_replace("\n", "", $text);
        $text = str_replace("\r", "", $text);
        $text = str_replace("\t", "", $text);
        if($convert_to_ascii) $text = Functions::utf8_to_ascii($text);
        while(preg_match("/  /", $text)) $text = str_replace("  ", " ", $text);
        return trim($text);
    }
}

?>