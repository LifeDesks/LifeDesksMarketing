<?php
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../conf.inc.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../db.class.php');
require_once "SolrAPI.php";

class LDTaxaIndexer
{
    private $db;
    private $server;
    private $user;
    private $password;
    private $database;
    private $solr;
    private $objects;
    private $solr_server;
    private $outfile;

    public function __construct($solr_server = SOLR_SERVER)
    {
        $this->server = DB_SERVER;
        $this->user = USERNAME;
        $this->password = PASSWORD;
        $this->database = DB_NAME;
        $this->db = new Database($this->server, $this->user, $this->password, $this->database);
        $this->outfile = OUTFILE;
    }

    function __destruct()
    {
        @unlink($this->outfile);
    }

    public function index($optimize = TRUE)
    {
        if(!defined('SOLR_SERVER') || !SolrAPI::ping(SOLR_SERVER, 'lifedesks_taxa')) return false;
        $this->solr = new SolrAPI(SOLR_SERVER, 'lifedesks_taxa');
        $this->solr->delete_all_documents();

        $start = 0;
        $max_id = 0;
        $limit = 100000;

        $result = $this->db->query_first("SELECT MIN(id) as min, MAX(id) as max FROM lifedesk_taxa");
        $start = $result["min"];
        $max_id = $result["max"];

        for($i=$start ; $i<$max_id ; $i+=$limit)
        {
            unset($this->objects);
            $this->lookup_taxa($i, $limit);
            if(isset($this->objects)) $this->solr->send_attributes($this->objects);
        }


        if(isset($this->objects)){
            $this->solr->send_attributes($this->objects);
        }
        $this->solr->commit();
        if($optimize) $this->solr->optimize();
    }


    function lookup_taxa($start, $limit)
    {
        echo "\nquerying names\n";
        $outfile = $this->select_into_outfile("SELECT id, shortname, '', tid, name, '', scientific, treepath, '', '' FROM lifedesk_taxa WHERE id BETWEEN $start AND ".($start+$limit));
        echo "done querying names\n";

        $RESULT = fopen($outfile, "r");
        while(!feof($RESULT))
        {
            if($line = fgets($RESULT, 4096))
            {
                $parts = explode("\t", rtrim($line, "\n"));
                $id = $parts[0];
                $shortname = $parts[1];
                $tid = $parts[3];
                $name = $parts[4];
                $scientific = $parts[6];
                $treepath = $parts[7];

                if($shortname || $name || $treepath)
                {
                    $this->objects[$id]['id'] = $id;
                    $shortname_new = SolrApi::text_filter($shortname);
                    $this->objects[$id]['shortname'] = $shortname_new;
                    $this->objects[$id]['tid'] = $tid;
                    $name_new = SolrApi::text_filter($name);
                    $this->objects[$id]['name'] = $name_new;
                    $this->objects[$id]['scientific'] = $scientific;
                    $treepath_new = SolrApi::text_filter($treepath);
                    $this->objects[$id]['treepath'] = $treepath_new;
                }
            }
        }
        fclose($RESULT);
        unlink($outfile);
    }

    function select_into_outfile($query, $escape = false)
    {
        $query = str_replace("\n", " ", $query);
        $query = str_replace("\r", " ", $query);
        $query = str_replace("\t", " ", $query);

        // perpare the command line command
        $command = MYSQL_BIN_PATH . " --host=$this->server --user=$this->user --password=$this->password --database=$this->database --compress --column-names=false --port=3306 -e \"$query\"  > $this->outfile";
        echo $command."\n";
        $output = shell_exec($command);
        if(file_exists($this->outfile) && filesize($this->outfile)) return $this->outfile;
        return false;
    }
}
?>