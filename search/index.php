<?php
require_once('../conf/vars.inc.php');
require_once('../conf/db.class.php');
require_once("../conf/solr/SolrAPI.php");
require_once("Paginator.php");

try {
  $solr_online = SolrAPI::ping(SOLR_SERVER, 'lifedesks');
  if(!$solr_online) {
    notify_lifedesks_team("Unable to connect to the SOLR Server for LifeDesks. Please contact your system administrator.");
  }
}
catch (Exception $e) {
  notify_lifedesks_team($e);
}

$db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);

global $db;

if(!$solr_online) {
	$dead = 'Sorry, our search service is currently offline. We are aware of the issue and are working to resolve it.';
}

$results = false;
$limit = 10;
$query = isset($_GET['q']) ? $_GET['q'] : false;
$type = isset($_GET['type']) ? $_GET['type'] : false;
$format = isset($_GET['format']) ? $_GET['format'] : false;

$start = isset($_GET['start']) ? abs((int)$_GET['start']) : 0;
$truestart = ($start == 1) ? 0 : $start;

$additionalParameters = array();
$shortname = isset($_GET['site']) ? $_GET['site'] : '';
$shortname = ($shortname == 'all') ? '' : $shortname;
if($shortname) {
  $db->query("SET NAMES 'utf8'");
  $qry = "SELECT solr_hash FROM drupal_site WHERE shortname='" . $db->escape($shortname) . "'";
  $row = $db->query_first($qry);
	if($row) {
	  $additionalParameters = array('fq' => 'hash:' . $row['solr_hash']);
	}
  }

if ($query && $solr_online) {

  // create a new solr service instance - host, port, and webapp
  // path (all defaults in this example)
  $solr_content = new SolrAPI(SOLR_SERVER, 'lifedesks');
  $solr_taxa = new SolrAPI(SOLR_SERVER, 'lifedesks_taxa');

  // if magic quotes is enabled then stripslashes will be needed
  if (get_magic_quotes_gpc() == 1) {
    $query = stripslashes($query);
  }

  // in production code you'll always want to use a try /catch for any
  // possible exceptions emitted  by searching (i.e. connection
  // problems or a query parsing error)
  try {
      switch($type) {
          case 'taxa':
                $results = $solr_taxa->query($query, $truestart, $limit, $additionalParameters);
            break;
          
          case 'sites':
                $results = lifedesks_found($query, $truestart, $limit, $additionalParameters);
            break;
          
          default:
                $add_param = '';
                if($type == 'taxonpages') $type = 'taxon_description';
                if($type == 'images') $type = 'image';
                if($type == 'bibliographies') $type = 'biblio';
                if($type == 'maps') $type = 'simplemappr';
                $type_param = (($type == 'taxon_description') || ($type == 'image') || ($type == 'biblio') || ($type == 'simplemappr')) ? " AND type:" . $type : "";
                $results = $solr_content->query($query.$type_param, $truestart, $limit, $additionalParameters);
      }
  }
  catch (Exception $e) {
    notify_lifedesks_team($e);
  }
}

function notify_lifedesks_team($exception) {
  require '../conf/phpmailer/class.phpmailer.php';
  try {
    $mail = new PHPMailer(true); //New instance, with exceptions enabled
    $body             = 'LifeDesks Solr service is broken or offline. ERROR: ' . ( is_string($exception) ? $exception : $exception->errorMessage());
    $mail->IsSMTP();                           // tell the class to use SMTP
    $mail->SMTPAuth   = false;
    $mail->Port       = 25;                    // set the SMTP server port
    $mail->Host       = SMTP_SERVER;        // SMTP server
    $mail->From       = 'lifedesks@eol.org';
    $mail->FromName   = "LifeDesks Team";
    $mail->AddAddress('lifedesks@eol.org');
    $mail->AddAddress('prodalerts@eol.org');
    $mail->Subject  = 'LifeDesks Solr service is broken or offline';
    $mail->WordWrap   = 80;                    // set word wrap
    $mail->Body = $body;
    $mail->IsHTML(false);
    $mail->Send();
  }
  catch (phpmailerException $e) {
    error_log($e->errorMessage());
  }
}

function lifedesks_found($query, $truestart, $limit, $additionalParameters) {
  global $db;

  $data = new stdClass;
  $data->response->docs = array();

  $rows = array();
  $db->query("SET NAMES 'utf8'");
  $query = strtolower($query);
  $qry = "SELECT
          ds.shortname, ds.stats, n.created
        FROM drupal_site ds
        INNER JOIN node n ON (ds.nid = n.nid)
        WHERE
          ds.profile='expert'
        AND
          ds.stats LIKE LOWER('%" . $db->escape($query) . "%')
        LIMIT ". $truestart .", ". $limit;
  $rows = $db->fetch_all_array($qry);

  foreach($rows as $row) {
	  $data->response->docs[$row['shortname']]['shortname'] = $row['shortname'];
	  $data->response->docs[$row['shortname']]['stats'] = $row['stats'];
	  $data->response->docs[$row['shortname']]['created'] = $row['created'];
  }

  $qry2 = "SELECT
          COUNT(ds.shortname) as sum
        FROM drupal_site ds
        INNER JOIN node n ON (ds.nid = n.nid)
        WHERE
          ds.profile='expert'
        AND
          ds.stats LIKE LOWER('%" . $db->escape($query) . "%')";

  $rows = $db->query_first($qry2);

  $data->response->numFound = $rows['sum'];
  return $data;
}

function _build_search_blocks() {
	
	global $db;
	
	$query = (isset($_GET['q'])) ? strtolower($_GET['q']) : '';
	$host = parse_url(ADMIN_URL);
    $domain = explode('.',$host['host'],2);
	
	$output = '';
	
	//get a snapshot of sites 
	$db->query("SET NAMES 'utf8'");
	$qry = "SELECT
	          ds.shortname, ds.stats
	        FROM drupal_site ds
	        INNER JOIN node n ON (ds.nid = n.nid)
	        WHERE
	          ds.profile='expert'
	        AND
	          ds.stats LIKE LOWER('%" . $db->escape($query) . "%') LIMIT 5";
	$qry2 = "SELECT
		          COUNT(ds.shortname) as sum
		        FROM drupal_site ds
		        INNER JOIN node n ON (ds.nid = n.nid)
		        WHERE
		          ds.profile='expert'
		        AND
		          ds.stats LIKE LOWER('%" . $db->escape($query) . "%')";
		
	$sites = $db->fetch_all_array($qry);
	$sitecount = $db->query_first($qry2);
	
	if($sites) {
		$output .= '<div class="search-sites search-block">';
		$output .= '<h3><a href="/search/?q=' . $_GET['q'] . '&type=sites">Sites</a></h3>';
		foreach($sites as $site) {
			$stats = unserialize($site['stats']);
			if($stats) {
				$title = htmlspecialchars($stats['site_title'], ENT_NOQUOTES, 'utf-8');
	            $owner_uid = htmlspecialchars($stats['users']['uid'], ENT_NOQUOTES, 'utf-8');
	            $owner_surname = htmlspecialchars($stats['site_owner']['surname'], ENT_NOQUOTES, 'utf-8');
	            $owner_givenname = htmlspecialchars($stats['site_owner']['givenname'], ENT_NOQUOTES, 'utf-8');
	
				$output .= '<span><a href="http://' . $site['shortname'] . '.' . $domain[1] .  '">' . $title . '</a></span>';
			}
		}
		if($sitecount['sum'] > 5) {
			$output .= '<a href="/search/?q=' . $_GET['q'] . '&type=sites">More &gt;&gt;</a>';
		}
		
		$output .= '</div>';
	}

    $solr_taxa = new SolrAPI(SOLR_SERVER, 'lifedesks_taxa');
    $results = $solr_taxa->query($query, 0, 5, array());
	if($results->response->docs) {
		$output .= '<div class="search-taxa search-block">';
		$output .= '<h3><a href="/search/?q=' . $_GET['q'] . '&type=taxa">Taxon Names</a></h3>';

		foreach($results->response->docs as $doc) {
		  $name = htmlspecialchars($doc->name, ENT_NOQUOTES, 'utf-8');
	      $tid = htmlspecialchars($doc->tid, ENT_NOQUOTES, 'utf-8');
	      $shortname = htmlspecialchars($doc->shortname, ENT_NOQUOTES, 'utf-8');
	      $scientific = htmlspecialchars($doc->scientific, ENT_NOQUOTES, 'utf-8');
	      $treepath = htmlspecialchars($doc->treepath, ENT_NOQUOTES, 'utf-8');
	      $icon = '<span class="taxon-icon"></span>';
	      $url = 'http://' . $shortname . '.' . $domain[1] .  '/';
	      $output .= '<span><a href="' . $url . 'pages/'. $tid .'">' . $name . '</a></span>' . "\n";
		}
		
		if($results->response->numFound > 5) {
			$output .= '<a href="/search/?q=' . $_GET['q'] . '&type=taxa">More &gt;&gt;</a>';
		}
		
		$output .= '</div>';
	}
	
	echo $output;
}

function _build_search_output($results, $format='html') {
  $output = '';
  $output_sites = '';
  foreach ($results->response->docs as $doc) {
	  $title = htmlspecialchars($doc->title, ENT_NOQUOTES, 'utf-8');
      $site = htmlspecialchars($doc->site, ENT_NOQUOTES, 'utf-8');
      $url = htmlspecialchars($doc->url, ENT_NOQUOTES, 'utf-8');
      $created = htmlspecialchars($doc->created, ENT_NOQUOTES, 'utf-8');
      $changed = htmlspecialchars($doc->changed, ENT_NOQUOTES, 'utf-8');
      $type = htmlspecialchars($doc->type_name, ENT_NOQUOTES, 'utf-8');
      $teaser = htmlspecialchars($doc->teaser, ENT_NOQUOTES, 'utf-8');

      $thumbnail = '';
      switch($type) {

	    case 'Taxon Page':
	      if(isset($doc->tid)) {
		    $url = (is_array($doc->tid)) ? $site . 'pages/' . $doc->tid[0] : $site . 'pages/' . $doc->tid;
	      }
	      $icon = '<span class="taxonpage-icon"></span>';
	    break;

	    case 'Image':
	      $thumbnail = (isset($doc->ss_image_absolute)) ? '<div class="result_thumbnail"><a href="' . $url . '"><img src="' . $doc->ss_image_absolute . '" alt="' . $title . '" /></a></div>' : '';
	      $icon = '<span class="image-icon"></span>';
	    break;

	    case 'Biblio':
	      $icon = '<span class="biblio-icon"></span>';
	    break;
	
	    case 'Shaded Map':
	      $icon = '<span class="simplemappr-icon"></span>';
	    break;
	 
	    default:
	      $icon = '<span class="page-icon"></span>';
      }

      switch ($format) {
        case 'html':
            $output .= '<div class="result_block">' . "\n";
            $output .= '<div class="result_title">' . $icon . '<a href="' . $url . '">' . $title . '</a></div>' . "\n";
            $output .= '<div class="context">' . $thumbnail . $teaser . '</div>' . "\n";
            $output .= '<div class="infoline">' . $url . ' [created: ' . $created . ' updated: ' . $changed . ']</div>' . "\n";
            $output .= '</div>' . "\n";
          break;

        case 'rss':
            $output .= '<item>' . "\n";
            $output .= '<title>' . $title . '</title>' . "\n";
            $output .= '<link>' . $url . '</link>' . "\n";
            $output .= '<description><![CDATA[' . $thumbnail . $teaser . ']]></description>' . "\n";
            $output .= '</item>' . "\n";
          break;
      }
  }
  echo $output;
}

function _build_taxa_search_output($results) {
    $host = parse_url(ADMIN_URL);
    $domain = explode('.',$host['host'],2);
    $output = '';
    foreach ($results->response->docs as $doc) {
      $name = htmlspecialchars($doc->name, ENT_NOQUOTES, 'utf-8');
      $tid = htmlspecialchars($doc->tid, ENT_NOQUOTES, 'utf-8');
      $shortname = htmlspecialchars($doc->shortname, ENT_NOQUOTES, 'utf-8');
      $scientific = htmlspecialchars($doc->scientific, ENT_NOQUOTES, 'utf-8');
      $treepath = htmlspecialchars(str_replace("Array->", "", $doc->treepath), ENT_NOQUOTES, 'utf-8');
      $icon = '<span class="taxon-icon"></span>';
      $url = 'http://' . $shortname . '.' . $domain[1] .  '/';

      $output .= '<div class="result_block">' . "\n";
      $output .= '<div class="result_title">' . $icon . '<a href="' . $url . 'pages/'. $tid .'">' . $name . '</a></div>' . "\n";
      $output .= '<div class="context">' . $treepath . '</div>' . "\n";
      $output .= '<div class="infoline">' .$url . 'pages/'. $tid . '</div>' . "\n";
      $output .= '</div>' . "\n";

    }
    echo $output;
}

function _build_lifedesks_search_output($results) {
    $host = parse_url(ADMIN_URL);
    $domain = explode('.',$host['host'],2);
    $output = '';
    foreach ($results->response->docs as $doc) {
        $shortname = htmlspecialchars($doc['shortname'], ENT_NOQUOTES, 'utf-8');
        $created = htmlspecialchars($doc['created'], ENT_NOQUOTES, 'utf-8');
        $url = 'http://' . $shortname . '.' . $domain[1] .  '/';
        $thumbnail = '<div class="result_thumbnail"><img src="/images/spider.png" alt="Image coming soon..." /></div>'; //will be overwritten if there is a thumbnail
        $stats = unserialize($doc['stats']);
        if ($stats) {
            $title = htmlspecialchars($stats['site_title'], ENT_NOQUOTES, 'utf-8');
            $owner_uid = htmlspecialchars($stats['users']['uid'], ENT_NOQUOTES, 'utf-8');
            $owner_surname = htmlspecialchars($stats['site_owner']['surname'], ENT_NOQUOTES, 'utf-8');
            $owner_givenname = htmlspecialchars($stats['site_owner']['givenname'], ENT_NOQUOTES, 'utf-8');
            $names_count = htmlspecialchars($stats['count_names'], ENT_NOQUOTES, 'utf-8');
            $users_count = htmlspecialchars($stats['users']['num_accounts'], ENT_NOQUOTES, 'utf-8');
            $biblio_count = 0; $image_count = 0; $taxon_page_count = 0;
            if(!empty($stats['media'])) {
              foreach($stats['media'] as $media) {
                switch ($media['type']) {
                    case 'Biblio':
                        $biblio_count = $media['count'];
                      break;

                    case 'Image':
	                    $thumb = $url . htmlspecialchars($media['latest']['path'],ENT_QUOTES);
                        $thumb_title = $media['latest']['title'];
	                    $thumb_url = $url . $media['latest']['url'];
	                    $image_count = $media['count'];
	                    $thumbnail = '<div class="result_thumbnail"><a href="' . $thumb_url . '"><img src="' . $thumb . '" alt="' . $thumb_title . '" /></a></div>';
                      break;

                    case 'Taxon Page':
                        $taxon_page_count = $media['count'];
                      break;
                }
              }
            }
        }
        $output .= '<div class="result_block">' . "\n";
        $output .= '<div class="result_title"><span class="site-icon"></span><a href="' . $url . '">' . $title . '</a></div>' . "\n";
        $output .= '<div class="latest_image">'.$thumbnail.'</div>';
        $output .= '<div class="site_details">';
        $output .= 'Taxon pages: '.$taxon_page_count . "<br />";
        $output .= 'Images: '.$image_count . "<br />";
        $output .= 'Biblio items: '.$biblio_count . "<br />";
        $output .= 'Taxon names: '.$names_count . "<br />";
        $output .= 'Users: '.$users_count . "<br />";
        $output .= '</div>';
        $output .= '<div class="context"><b>Coordinator:</b> <a href = "'.$url.'user/'.$owner_uid.'">' . $owner_surname . ', '. $owner_givenname .'</a></div>' . "\n";
        $output .= '<div class="infoline">'.$url.' [created: ' . gmdate('Y-m-dTh:m:s', $created) . ']</div>'."\n";
        $output .= '</div>' . "\n";
    }
    echo $output;
}

if($format == 'rss' && $results) {
  header('Content-Type: application/rss+xml; charset=utf-8');

  $rssTotal = $results->response->numFound;
  $rssStartIndex = ($start == 0) ? 1 : $start;

  echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
  echo '<rss version="2.0" xml:base="' . BASE_URL . '" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:opensearch="http://a9.com/-/spec/opensearch/1.1/" xmlns:atom="http://www.w3.org/2005/Atom">' . "\n";
  echo '<channel>' . "\n";
  echo '<atom:link rel="search" href="' . BASE_URL . '/search/opensearch/" type="application/opensearchdescription+xml"></atom:link>' . "\n";
  echo '<title>LifeDesks Search</title>' . "\n";
  echo '<link>' . BASE_URL . '</link>' . "\n";
  echo '<description>Search across all LifeDesks</description>' . "\n";
  echo '<language>en</language>' . "\n";
  echo '<opensearch:totalResults>' . $rssTotal . '</opensearch:totalResults>' . "\n";
  echo '<opensearch:startIndex>' . $rssStartIndex . '</opensearch:startIndex>' . "\n";
  echo '<opensearch:itemsPerPage>' . $limit . '</opensearch:itemsPerPage>' . "\n";
  echo '<opensearch:Query role="request" searchTerms="' . htmlspecialchars($query) . '"></opensearch:Query>' . "\n";

  _build_search_output($results, 'rss');

  echo "</channel>" . "\n";
  echo "</rss>" . "\n";
  exit;
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Search | LifeDesks</title>
<link rel="search" href="/search/opensearch/" type="application/opensearchdescription+xml" title="LifeDesks" />
<?php
if($query) {
  echo '<link rel="alternate" type="application/rss+xml" title="LifeDesks Search for ' . $query . '" href="/search/?q=' . htmlspecialchars($query) . '&start=' . $start . '&format=rss" />';
}
?>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
</head>
<body>

<div id="wrapper">
	<div id="container">

	<?php echo $VARS_BANNER; ?>

	<!-- content -->
	<div id="content" class="search_subpage">

        <form method="get" action="/search/" class="search_searchform">
Search for: <input type="text" name="q" size="20" value="<?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?>" id="search_searchbox_form" class="search_searchbox" />
<input type="hidden" name="site" value="<?php echo (isset($_GET['site'])) ? $_GET['site'] : 'all'; ?>" />
<input type="hidden" name="type" value="<?php echo (isset($_GET['type'])) ? $_GET['type'] : ''; ?>" />
<input type="submit" value="Submit" class="search_button" />
        </form>
        <?php
            $total = ($query && $solr_online) ? (int)$results->response->numFound : 0;
            $start = min(($start==0) ? 1 : $start, $total);
            $end = min($start+$limit-1, $total);

            if($total > 0) {
                $output = 'Results ' . $start . '-' . $end . ' of ' . $total . ' found';
            }
            else {
                $output = '<em>no content found</em>';
            }
        ?>
            <div class="searchheading">Search results for: <?php echo htmlspecialchars($query, ENT_QUOTES, 'utf-8'); ?><br /><br /></div>
            <div class="summary">
                <?php
                    echo $output;
                    $addparam = "&type=";
                ?>
                <br />
            </div>
            <!-- left navigation -->
                <div id="navigation">
                        <ul class = "search_menu">

                        <?php if($type == 'everything' || !$type): ?>
                          <li class="everythingicon-selected mitem">Everything</li>
                        <?php else: ?>
                          <li class="everythingicon mitem"><a href="?q=<?php echo $query.$addparam ?>everything">Everything</a></li>
                        <?php endif; ?>

                        <?php if($type == 'sites'): ?>
                          <li class="siteicon-selected mitem">Sites</li>
                        <?php else: ?>
                          <li class="siteicon mitem"><a href="?q=<?php echo $query.$addparam ?>sites">Sites</a></li>
                        <?php endif; ?>

                        <?php if($type == 'image'): ?>
                          <li class="imageicon-selected mitem">Images</li>
                        <?php else: ?>
                          <li class="imageicon mitem"><a href="?q=<?php echo $query.$addparam ?>images">Images</a></li>
                        <?php endif; ?>

                        <?php if($type == 'taxon_description'): ?>
                          <li class="taxonpageicon-selected mitem">Taxon Pages</li>
                        <?php else: ?>
                          <li class="taxonpageicon mitem"><a href="?q=<?php echo $query.$addparam ?>taxonpages">Taxon Pages</a></li>
                        <?php endif; ?>

                        <?php if($type == 'biblio'): ?>
                          <li class="biblioicon-selected mitem">Bibliographies</li>
                        <?php else: ?>
                          <li class="biblioicon mitem"><a href="?q=<?php echo $query.$addparam ?>bibliographies">Bibliographies</a></li>
                        <?php endif; ?>

                        <?php if($type == 'simplemappr'): ?>
                          <li class="simplemappricon-selected mitem">Maps</li>
                        <?php else: ?>
                          <li class="simplemappricon mitem"><a href="?q=<?php echo $query.$addparam ?>maps">Maps</a></li>
                        <?php endif; ?>

                        <?php if($type == 'taxa'): ?>
                          <li class="taxonicon-selected mitem">Taxon Names</li>
                        <?php else: ?>
                          <li class="taxonicon mitem"><a href="?q=<?php echo $query.$addparam ?>taxa">Taxon Names</a></li>
                        <?php endif; ?>

                        </ul>
                </div>
            <!-- /left navigation -->
            <!-- search results -->
            <div class="search_results">
                <div class="results">

                <?php
                // iterate result documents
                if($query && $solr_online) {
                  if($type == "taxa") {
                    _build_taxa_search_output($results);
                  } elseif ($type == "sites") {
                    _build_lifedesks_search_output($results);
                  } else {
	                if(!$type || $type == "everything") {
		              _build_search_blocks();
	                }
                    _build_search_output($results);
                  }
                if($total == 0) echo "<div id=\"recommended-links\"><p>Sorry, no content was found. <a href=\"/create/\">Make your own</a> LifeDesk and start developing content.</p></div>";
               }
               if(!$solr_online) {
	             echo "<div id=\"recommended-links\"><p>" . $dead . "</p></div>";
               }
                ?>
            </div>
            <!-- /search results -->
            <?php
                if($total>10) {
	                if($type) $query = $query . "&type=" . $type;
	                if($shortname) $query = $query . "&site=" . $shortname;
	                if($format) $query = $query . "&format=" . $format;
                    Paginator::paginate($truestart,$total,$limit,"?q=" . $query . "&start=");
                }
            ?>
        </div>
    </div>
	<!-- /content -->

	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->
<?php echo $VARS_NAVBAR; ?>
<?php echo $VARS_ANALYTICS; ?>
</body>
</html>