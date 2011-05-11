<?php
header('Content-Type: application/xml; charset=utf-8');

require_once('../../conf/vars.inc.php');
require_once('../../conf/db.class.php');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);
$sites = array();

$db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
$qry = "SELECT shortname, stats FROM drupal_site WHERE profile='expert' AND display = 1";
$rows = $db->fetch_all_array($qry);

foreach($rows as $row) {
	$stats = unserialize($row['stats']);
	   if($stats['site_title']) {
		   $data[] = array(
		     'subdomain' => $row['shortname'],
		     'title' => htmlspecialchars($stats['site_title']),
		   );
	   }
}
?>
<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<opml version="1.1">
<head>
<title>LifeDesks Classification Logs Feeds</title>
<dateCreated><?php print gmdate('D, d M Y H:i:s O',time()); ?></dateCreated>
</head>
<body>
<outline text="LifeDesks Classification Logs Folder">
<?php
foreach($data as $site) {
  echo '<outline title="' . $site['title'] . '" text="' . $site['title'] . '" type="rss" xmlUrl="http://' . $site['subdomain'] . '.' . $domain[1] . '/classification/logs/rss"></outline>' . "\n";	
}
?>
</outline>
</body>
</opml>