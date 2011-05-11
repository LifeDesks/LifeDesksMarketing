<?php
require_once "Paginated.php";
require_once "DoubleBarLayout.php";
require_once('../conf/vars.inc.php');
require_once('../conf/db.class.php');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);
$sorted = isset($_GET['sort']) ? $_GET['sort'] : '';

$sites = array();
$db = new Database(DB_SERVER,USERNAME,PASSWORD, DB_NAME);
$db->query("SET NAMES 'utf8'");
$qry = "SELECT 
          n.created, ds.shortname, ds.stats 
        FROM drupal_site ds 
        INNER JOIN node n ON (ds.nid = n.nid) 
        WHERE ds.profile='expert' AND ds.display = 1";
$data = array();
$rows = $db->fetch_all_array($qry);
foreach($rows as $row ) {
   $metadata = array(
     'subdomain' => $row['shortname'],
     'datecreated' => gmdate('M j, Y', $row['created']),
   );
   $stats = unserialize($row['stats']);
   if(!empty($stats)) {
     if(ENVIRONMENT == 'integration' && $stats['content_partner_file'] == 0) {
     }
     else {
       $data[] = array_merge($metadata,$stats);
     }
   }
}

$created_asc_selected = '';
$created_desc_selected = '';
$title_asc_selected = '';
$title_desc_selected = '';
$coord_asc_selected = '';
$coord_desc_selected = '';
$content_partner_selected = '';
$content_selected = '';

switch($sorted) {
  case 'created-asc':
    $created_asc_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $created[$key] = $row['created'];
    }
    array_multisort($created, SORT_ASC, $data);
  break;

  case 'created-desc':
    $created_desc_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $created[$key] = $row['created'];
    }
    array_multisort($created, SORT_DESC, $data);
  break;

  case 'title-asc':
    $title_asc_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $title[$key] = $row['site_title'];
    }
    array_multisort($title, SORT_ASC, $data);
  break;

  case 'title-desc':
    $title_desc_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $title[$key] = strtolower($row['site_title']);
    }
    array_multisort($title, SORT_DESC, $data);
  break;

  case 'coordinator-asc':
    $coord_asc_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $coordinator[$key] = strtolower($row['site_owner']['surname']);
    }
    array_multisort($coordinator, SORT_ASC, $data);
  break;

  case 'coordinator-desc':
    $coord_desc_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $coordinator[$key] = strtolower($row['site_owner']['surname']);
    }
    array_multisort($coordinator, SORT_DESC, $data);
  break;

  case 'content-partner':
    $content_partner_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $contentpartner[$key] = $row['content_partner_file'];
    }
    array_multisort($contentpartner, SORT_DESC, $data);
  break;

  case 'content':
    $content_selected = " selected='selected'";
    $param = '&sort=' . $sorted;
    foreach($data as $key => $row) {
	  $content = array();
	  foreach($row['media'] as $medium) {
	    $content[] = (int)$medium['count'];
	  }
      $withcontent[$key] = array_sum($content);
    }
    array_multisort($withcontent, SORT_DESC, $data);
  break;

  default:
    $order = '';
    $param = '';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Discover LifeDesks | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
<link rel="alternate" type="application/rss+xml" title="MediaRSS - LifeDesks Gallery" href="feed/rss.xml" />
<script type="text/javascript" src="/js/jquery.bgiframe.min.js"></script>
<!--[if IE]>
	<script type="text/javascript" src="/js/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="/js/jquery.bt.min.js"></script>
<script type="text/javascript" src="/js/lifedesksgallery.js"></script>
</head>
<body>

	<div id="wrapper">
		<div id="container">

	        <?php echo $VARS_BANNER; ?>

			
			<?php
			$page = isset($_GET['page']) ? $_GET['page'] : 1;

			$pagedResults = new Paginated($data, 8, $page);

            echo '<div id="site-sort">
			<form method="get">
			  <label for="sort">Sort by:</label>
			  <select id="site-sort-select" name="sort">
			    <option value="">--choose--</option>
				<option value="created-asc"'. $created_asc_selected . '>oldest first</option>
				<option value="created-desc"' . $created_desc_selected . '>newest first</option>
				<option value="title-asc"' . $title_asc_selected . '>site title &uarr;</option>
				<option value="title-desc"' . $title_desc_selected . '>site title &darr;</option>
				<option value="coordinator-asc"' . $coord_asc_selected . '>coordinator &uarr;</option>
				<option value="coordinator-desc"' . $coord_desc_selected . '>coordinator &darr;</option>
				<option value="content-partner"' . $content_partner_selected . '>EOL partner</option>
				<option value="content"' . $content_selected . '>content &darr;</option>
			  </select>
			<input type="hidden" name="page" value="' . $page . '"></input>
			</form>
			</div>' . "\n";
			
            echo "<div id='lifedesks_gallery'>" . "\n";

			echo "<ul>" . "\n";

			while($item = $pagedResults->fetchPagedRow()) {
			  $title = (strlen($item['site_title']) > 40) ? substr($item['site_title'],0,35) . "..." : $item['site_title'];
			  $biblio_count = '0';
				$thumb = '';
				$thumb_title = '';
				$thumb_url = '';
				$image_count = '0';
				$taxon_page_count = '0';
				$map_count = '0';
				
				if(!empty($item['media'])) {
				  foreach($item['media'] as $media) {
				  	switch ($media['type']) {
				  		case 'Biblio':
				          $biblio_count = $media['count'];
						break;
						case 'Image':
						  $thumb = 'http://' . $item['subdomain'] . '.' . $domain[1] .  '/' . htmlspecialchars($media['latest']['path'],ENT_QUOTES);
						  $thumb_title = $media['latest']['title'];
						  $thumb_url = 'http://' . $item['subdomain'] . '.' . $domain[1] . '/' . $media['latest']['url'];
						  $image_count = $media['count'];
						break;
						case 'Taxon Page':
						  $taxon_page_count = $media['count'];
						break;
						case 'Shaded Map':
						  $map_count = $media['count'];
						break;
					}
				  }
			    }
				echo "<li><h3><a href='http://{$item['subdomain']}.{$domain[1]}'>{$title}</a></h3>"; 
				echo "<div class='lifedesks_image'>";
				if($thumb) {
				  echo "<a href='{$thumb_url}'><img src='{$thumb}' class='thumb' alt='{$thumb_title}' title='{$thumb_title}'></a>";	
				}
				else {
				  echo  "<a href='http://{$item['subdomain']}.{$domain[1]}'><img src='/images/spider.png' class='thumb' alt='Image coming soon...' title='Image coming soon...'></a>";
				}
				if($item['content_partner_file']){
				  echo "<div class='lifedesks_partner'><img src='/images/content_partner.gif' alt='EOL Content Partner' title='EOL Content Partner'></div>";
				}
				echo "</div>";
				echo "<div class='lifedesks_curator'>";
				if($item['site_owner']) {
  				echo "<strong>Coordinator:</strong> <a href='http://{$item['subdomain']}.{$domain[1]}/user/{$item['site_owner']['uid']}'>{$item['site_owner']['givenname']} {$item['site_owner']['surname']}</a>";
				}
  				echo "</div>";
				echo "<div class='lifedesks_created'><strong>Created:</strong> {$item['datecreated']}</div>";
				echo "<div class='lifedesks_info'><span>More info <img src='/images/help.gif' alt='Site statistics for {$item['site_title']}' title='Site statistics for {$item['site_title']}'></span></div>";
				echo "<div class='lifedesks_statistics'>";
				echo "<div class='lifedesks_title'>{$item['site_title']}</div>";
				echo "<span><strong>Members:</strong> {$item['users']['num_accounts']}</span>";
				echo "<span><strong>Images:</strong> {$image_count}</span>";
				echo "<span><strong>Biblio Items:</strong> {$biblio_count}</span>";
				echo "<span><strong>Maps:</strong> {$map_count}</span>";
				echo "<span><strong>Species Pages:</strong> {$taxon_page_count}</span>";
				$count_names = $item['count_names'] ? $item['count_names'] : 0;
				echo "<span><strong>Taxonomic Names:</strong> {$count_names}</span>";
				echo "</div>";
				echo "</li>" . "\n";
			}

			echo "</ul>" . "\n";
			
			echo "</div>" . "\n";
			
			echo "<div style='clear:both'></div>";

			//important to set the strategy to be used before a call to fetchPagedNavigation
			$pagedResults->setLayout(new DoubleBarLayout());
			echo '<div id="paginator">';
			echo $pagedResults->fetchPagedNavigation($param);
			echo '</div>';
			?>

	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->
<?php echo $VARS_NAVBAR; ?>
<?php echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>
