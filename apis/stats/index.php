<?php
header('Content-Type: application/xml; charset=utf-8');

require_once('../../conf/vars.inc.php');
require_once('../../conf/db.class.php');
require_once('../../conf/arrayToXML.inc');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);

$db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
$db->query("SET NAMES 'utf8'");
$qry = "SELECT n.created, ds.shortname, ds.stats FROM drupal_site ds INNER JOIN node n ON (ds.nid = n.nid) WHERE ds.profile='expert' AND ds.display = 1";
$rows = $db->fetch_all_array($qry);

foreach($rows as $row) {
	$metadata = array(
	     'site_url' => 'http://' . $row['shortname'] . '.' . $domain[1],
	     'datecreated' => gmdate('M j, Y', $row['created']),
	   );
	   $stats = unserialize($row['stats']);
	   if(!empty($stats) && $stats['content_partner_file'] == 1) {
	     $stats['content_partner_document'] = 'http://' . $row['shortname'] . '.' . $domain[1] . '/eol-partnership.xml.gz';	
	   }
	   if(!empty($stats)) {
	     $data[] = array_merge($metadata,$stats);
	   }
}

try {
    $xml = new array2xml('results', 'site');
    $xml->createNode($data);
    echo $xml;
} 
catch (Exception $e) {
    echo $e->getMessage();
}

?>