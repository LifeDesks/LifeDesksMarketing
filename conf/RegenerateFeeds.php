<?php
require_once('conf.inc.php');
require_once('db.class.php');
require_once('rss.generator.class.php');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);


/****************************************
 * Generate Sites RSS feed
 ***************************************/

$rss = new RSSGenerator();
$rss->setVersion("2.0");
$rss->addNameSpace("xmlns:media=\"http://search.yahoo.com/mrss/\"");
$rss->setAtomLink("http://$domain[1]/sites/feed/rss.xml");
$rss->setTitle("LifeDesks Image Gallery");
$rss->setLink("http://www.$domain[1]/sites/");
$rss->setDescription("LifeDesks Image Gallery");

$db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
$db->query("SET NAMES 'utf8'");
$qry = "SELECT shortname FROM drupal_site WHERE profile='expert' AND display = 1";
$rows = $db->fetch_all_array($qry);
$queries = array();
$items = array();
foreach($rows as $row) {
	$queries[] = "(SELECT 
				n.nid, n.title, n.created, td.tid, '".$row['shortname']."' AS shortname
			FROM 
				".$row['shortname'].".node n  
			LEFT JOIN 
				(".$row['shortname'].".term_node tn INNER JOIN ".$row['shortname'].".term_data td ON (tn.tid = td.tid) INNER JOIN ".$row['shortname'].".vocabulary v ON (td.vid = v.vid)
				) ON (n.nid = tn.nid)  
			WHERE
				n.status = 1 AND n.type = 'image' AND v.name = 'Taxa'
			ORDER BY
				n.created DESC 
			LIMIT 5)";
}
if($rows) {
  $query  = implode(" UNION ALL ", $queries);
  $query .= " ORDER BY created DESC LIMIT 100";
  $items = $db->fetch_all_array($query);
}

$exists = array();
foreach($items as $item) {
	$id = $item['shortname'].$item['nid'];
	$image = getimagesize("http://".$item['shortname'].".$domain[1]/image/view/".$item['nid']."/_original");
	if($image && !array_key_exists($id, $exists)) {
		$item = array(
			"title" => $item['title'],
			"link" => ($item['tid']) ? "http://".$item['shortname'].".$domain[1]/pages/".$item['tid'] : "http://".$item['shortname'].".$domain[1]/node/".$item['nid'],
			"pubDate" => (int)$item['created'],
			"guid" => "http://".$item['shortname'].".$domain[1]/image/view/".$item['nid']."/thumbnail",
			"description" => "",
			"media:thumbnail" => array(
				"url" => "http://".$item['shortname'].".$domain[1]/image/view/".$item['nid']."/thumbnail",
			),
			"media:content" => array(
				"url" => "http://".$item['shortname'].".$domain[1]/image/view/".$item['nid']."/_original",
				"type" => $image['mime'],
				"height" => $image[1],
				"width" => $image[0],
			),
		);
		$exists[$id] = true;
		$rss->addItem($item);
	}
}

$xmlHandle = fopen(SITES_RSS, "w");
fwrite($xmlHandle, $rss->createRSS());
fclose($xmlHandle);
 

/****************************************
 * Generate Classifications RSS Feed
 ***************************************/ 

$rss = new RSSGenerator();
$rss->setVersion("2.0");
$rss->addNameSpace("xmlns:media=\"http://search.yahoo.com/mrss/\"");
$rss->setAtomLink("http://www.$domain[1]/classifications/feed/rss.xml");
$rss->setTitle("LifeDesks Classifications");
$rss->setLink("http://www.$domain[1]/classifications");
$rss->setDescription("LifeDesks Classifications");

$classifications = array();
$db->query("SET NAMES 'utf8'");
$qry = "SELECT shortname, classification FROM drupal_site WHERE profile='expert' AND classification != ''";
$rows = $db->fetch_all_array($qry);
foreach($rows as $row) {
	$classifications[$row['shortname']] = unserialize($row['classification']);
}

$rss->sort($classifications, "updated");

foreach($classifications as $shortname => $data) {
	$item = array(
		'title' => $shortname . " classification",
		'link' => "http://$shortname.$domain[1]/classification.tar.gz",
		'pubDate' => $data['updated'],
		'guid' => "http://$shortname.$domain[1]/classification.tar.gz",
		'description' => $data['citation'] . ".<p>" . $data['description'] . "</p>[version " . $data['version'] . ", Creative Commons " . $data['license'] . ", download: <a href='http://$shortname.$domain[1]/classification.tar.gz'>http://$shortname.$domain[1]/classification.tar.gz</a>",
	);
	$rss->addItem($item);
}

$xmlHandle = fopen(CLASSIFICATIONS_RSS, "w");
fwrite($xmlHandle, $rss->createRSS());
fclose($xmlHandle);
 

/****************************************
 * Generate New Features RSS feed
 ***************************************/ 

$rss = new RSSGenerator();
$rss->setVersion("2.0");
$rss->addNameSpace("xmlns:media=\"http://search.yahoo.com/mrss/\"");
$rss->setAtomLink("http://www.$domain[1]/newfeatures/feed/rss.xml");
$rss->setTitle("LifeDesks New Features");
$rss->setLink("http://www.$domain[1]/newfeatures");
$rss->setDescription("LifeDesks New Features");

$releases = array();
$release_data = implode("", file(ADMIN_URL . "/lifedesk-releases"));
if($release_data) $releases = json_decode($release_data);
$release = array_shift($releases->releases); //only include the first element in $releases->releases array

if($release->bug_fixes) {
  $description = htmlspecialchars("<p>Bug Fixes: ");
  $numbugs = count($release->bug_fixes);
  $i = 1;
  foreach($release->bug_fixes as $bug_fix) {
    $description .= htmlspecialchars($bug_fix->value);
    if($i < $numbugs) $description .= "; ";
    $i++;
  }
  $description .= htmlspecialchars("</p>");
}
if($release->new_features) {
  $description .= htmlspecialchars("<p>New Features: ");
  $numfeatures = count($release->new_features);
  $i = 1;
  foreach($release->new_features as $new_feature) {
    $description .= htmlspecialchars($new_feature->value);
    if($i < $numfeatures) $description .= "; ";
    $i++;
  }
  $description .= htmlspecialchars("</p>");
}

$item = array(
	'title' => "LifeDesks release $release->version ($release->release_date)",
	'link' => "http://www.$domain[1]/newfeatures",
	'pubDate' => (int)$release->published_date,
	'guid' => "http://www.$domain[1]/newfeatures",
	'description' => $description,
);
$rss->addItem($item);

$xmlHandle = fopen(NEWFEATURES_RSS, "w");
fwrite($xmlHandle, $rss->createRSS());
fclose($xmlHandle);


/****************************************
 * Generate Current Tasks RSS feed
 ***************************************/

$rss = new RSSGenerator();
$rss->setVersion("2.0");
$rss->addNameSpace("xmlns:media=\"http://search.yahoo.com/mrss/\"");
$rss->setAtomLink("http://$domain[1]/sites/feed/rss.xml");
$rss->setTitle("LifeDesks Current Tasks");
$rss->setLink("http://www.$domain[1]/newfeatures/current");
$rss->setDescription("LifeDesks Current Tasks");

$iteration = array();
$current_tasks = implode("", file(ADMIN_URL . "/lifedesk-current"));
if($current_tasks) $iteration = json_decode($current_tasks);
$iteration = array_shift($iteration->tasks); //only include the first element in $iteration->tasks array

if($iteration->tickets) {
  $description  = htmlspecialchars("<p>Expected Release Date: ". $iteration->expected_release_date . "</p>");
  $description .= htmlspecialchars("<p>Current Tasks: ");
  $numtickets = count($iteration->tickets);
  $i = 1;
  foreach($iteration->tickets as $ticket) {
    $description .= htmlspecialchars($ticket->value);
    if($i < $numtickets) $description .= "; ";
    $i++;
  }
  $description .= htmlspecialchars("</p>");
}

$item = array(
	'title' => "LifeDesks current iteration $iteration->current_version ($iteration->expected_release_date)",
	'link' => "http://www.$domain[1]/newfeatures/current",
	'pubDate' => (int)$iteration->published_date,
	'guid' => "http://www.$domain[1]/newfeatures/current/$iteration->current_version",
	'description' => $description,
);
$rss->addItem($item);

$xmlHandle = fopen(CURRENTTASKS_RSS, "w");
fwrite($xmlHandle, $rss->createRSS());
fclose($xmlHandle);
?>