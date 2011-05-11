<?php
require_once('../../conf/conf.inc.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../conf/solr/SolrAPI.php');
require_once( str_replace('//','/',dirname(__FILE__).'/') .'../../conf/db.class.php');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);
$taxa = isset($_GET['taxa']) ? $_GET['taxa'] : "";
$callback = isset($_GET['callback']) ? $_GET['callback'] : "";

$sites = array();
$results['sites'] = array();

if($taxa) {
   $solr_taxa = new SolrAPI(SOLR_SERVER, 'lifedesks_taxa');
   $db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
   $db->query("SET NAMES 'utf8'");

   $taxa_array = array();
   $taxa = preg_replace("/[\s,;]+/", ",", stripslashes($taxa));
   $taxa_array = explode(",", $taxa);

   //see if Solr is accessible first
   $solr_online = SolrAPI::ping(SOLR_SERVER, 'lifedesks_taxa');
   if(!$solr_online) {
     $results['message'] = "Sorry, the search service is currently offline";
   }
   else {
	   foreach($taxa_array as $taxon) {
	     $solr_results = $solr_taxa->query($taxon, 0, 10, array());
	     foreach($solr_results->response->docs as $doc) {;
		   if(isset($results['sites'][$doc->shortname])) break;
		   	$qry = "SELECT
			          ds.stats
			        FROM drupal_site ds
			        WHERE
			          ds.profile='expert'
			        AND
			          ds.shortname = '" . $db->escape($doc->shortname) . "'";
			   $rows = $db->fetch_all_array($qry);
				foreach($rows as $row) {
					$stats = unserialize($row['stats']);
					if($stats && $stats['media']) {
						$sites[$doc->shortname]['subdomain'] = $doc->shortname;
						$sites[$doc->shortname]['url'] = 'http://' . $doc->shortname . '.' . $domain[1] .  '/';
						$sites[$doc->shortname]['stats'] = $stats;
					}
				}
	     }
	   }
   }
}

foreach($sites as $site) {
	$results['sites'][] = $site;
}

$results['total'] = count($results['sites']);
krsort($results);

$search = json_encode($results);
if($callback) {
    header('Content-type: text/javascript');
	$search = $callback . '(' . $search . ');';
}
else {
	header('Content-type: application/json');
}

print($search);

?>