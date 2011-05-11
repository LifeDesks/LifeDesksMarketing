<?php
require_once('../conf/vars.inc.php');
require_once('../conf/db.class.php');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);
$sites = array();

$db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
$db->query("SET NAMES 'utf8'");
$qry = "SELECT shortname, classification FROM drupal_site WHERE profile='expert' AND display = 1";
$rows = $db->fetch_all_array($qry);
$data = array();
foreach($rows as $row) {
   $metadata = array(
     'subdomain' => $row['shortname'],
   );
   $classification = unserialize($row['classification']);
   if(!empty($classification)) {
     if(ENVIRONMENT == 'integration') {
     }
     else {
       $data[] = array_merge($metadata,$classification);
     }
   }
}

$sort = array();
foreach($data as $key => $site) {
  $sort[$key] = $site['citation'];
}

array_multisort($sort, SORT_ASC, $data);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Checklists | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
<link rel="alternate" type="application/rss+xml" title="LifeDesks Checklists" href="feed/rss.xml" />
</head>
<body>
	<div id="wrapper">
		<div id="container">

	        <?php echo $VARS_BANNER; ?>

			<!-- content -->
			<div id="content" class="subpage">
				
				<div id="text">
					<h2 id="classifications">Checklists</h2>
					<div id="classifications-description"><img src="../images/cta1.gif" alt="shared classifications" align="right" style="margin-left:10px" />LifeDesk members and communities spend a great deal of time organizing their taxonomic checklists. Below are downloads from LifeDesk members who wish to make these available to you, the Encyclopedia of Life, and to biodiversity informatics projects. These checklists will also be shared with the <a href="http://www.globalnames.org">Global Names Architecture</a>. You may read more about this initiative <a href="http://help.lifedesks.org/files/help/gna.pdf">here (PDF)</a>. Creative Commons licensing and additional information are included in each download, which also contains a full hierarchy in Excel 2007 (.xlsx) and easily ingested <a href="http://www.gbif.org/informatics/standards-and-tools/publishing-data/data-standards/darwin-core-archives/">Darwin Core Archive</a> files.</div>

<?php
foreach($data as $site) {
	
	if(array_key_exists('citation', $site) && array_key_exists('success', $site)) {
	  $citation = strip_tags($site['citation']);
	  $updated = (array_key_exists('updated', $site)) ? ', updated ' . gmdate("M d, Y", $site['updated']) : '';
	  $updated = (array_key_exists('version', $site)) ? ' (v. ' . $site['version'] . $updated . ')': '';
	  $download = ($site['success']) ? '<span class="download">[<a href="http://' . $site['subdomain'] . '.' . $domain[1] . '/classification.tar.gz" onClick="javascript: pageTracker._trackPageview(\'/classifications/'.$site['subdomain'].'\');">download</a>]</span>': '';
	  $link = '<span class="view-site">[<a href="http://' . $site['subdomain'] . '.' . $domain[1] . '">view site</a>]</span>'; 
	  echo '<p class="classification-citation">' . $citation . $updated . $link . $download . '</p>' . "\n";
	}
}
?>
				</div>
				<img src="/images/bluebox_bottom_bg.gif" alt="" />
			</div><!-- /content -->
			
			<div id="sidebar">
				<div class="sidebanner"><span>Tell a friend about LifeDesks</span></div>
				<div class="sidecontent">
				<?php echo $VARS_TELLFRIEND; ?>
				</div><!-- /sidecontent -->
			</div><!-- /sidebar -->

	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->
<?php echo $VARS_NAVBAR; ?>
<?php if(ENVIRONMENT == 'production') echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>
