<?php
require_once('../conf/vars.inc.php');

/**
 * Projects to exclude from download
 * Usage: if flag set to true, a link will be offered to the drupal.org project page 
 * (e.g. http://drupal.org/project/biblio)
 */
$excluded_download = array(
	'ajax_pic_preview' => true,
	'apachesolr' => true,
	'apachesolr_image' => true,
	'apachesolr_search' => true,
	'administerusersbyrole' => true,
	'auto_nodetitle' => true,
	'menu_admin_per_menu' => true,
	'biblio' => true,
	'biblio_pm' => true,
	'captcha' => true,
	'cmf' => true,
	'config_perms' => true,
	'content' => true,
	'content_copy' => true,
	'content_multigroup' => true,
	'content_permissions' => true,
	'creativecommons_lite' => true,
	'ctm' => true,
	'fieldgroup' => true,
	'front' => true,
	'i18n' => true,
	'i18nblocks' => true,
	'i18ncck' => true,
	'i18ncontent' => true,
	'i18nmenu' => true,
	'i18npoll' => true,
	'i18nprofile' => true,
	'i18nstrings' => true,
	'i18nsync' => true,
	'i18ntaxonomy' => true,
	'i18nviews' => true,
	'i18n_test' => true,
	'image' => true,
	'image_captcha' => true,
	'invite' => true,
	'invite_cancel_account' => true,
	'invite_stats' => true,
	'jquery_ui' => true,
	'memcache' => true,
	'memcache_admin' => true,
	'nodereference' => true,
	'number' => true,
	'oai2' => true,
	'opensearch' => true,
	'option_widgets' => true,
	'override_node_options' => true,
	'path_redirect' => true,
	'permissions_api' => true,
	'piclens' => true,
	'protect_critical_users' => true,
	'recaptcha' => true,
	'recaptcha_mailhide' => true,
	'role' => true,
	'role_delegation' => true,
	'smtp' => true,
	'text' => true,
	'token' => true,
	'transaction' => true,
	'update' => true,
	'update_notification_disable' => true,
	'userreference' => true,
	'wysiwyg' => true,
	);

$excluded_listing = array(
	'ajax_logo_preview',
	'apachesolr_nodeaccess',
	'apachesolr_og',
	'biblio_pm',
	'compare_schema_required',
	'deleted_sites',
	'drupal',
	'lifedesk_alter',
	'lifedesk_announcements',
	'lifedesk_current',
	'lifedesk_foot',
	'lifedesk_releases',
	'lifedesk_stats',
	'lifedesk_support',
	'locale',
	'mass_contact',
	'reset_superadmin_pass',
	);
	
/**
 * Recursive function to scan a directory and its child directories
 */
function rscandir($base='', &$data=array()) {
  if(is_dir($base)) {
	  $array = array_diff(scandir($base), array('.', '..'));
	  foreach($array as $value) {
	    if (is_dir($base.$value)) {
	      $data[] = $base.$value.'/';
	      $data = rscandir($base.$value.'/', $data);
	    }
	    elseif (is_file($base.$value)) {
	      $data[] = $base.$value;
	    }
	  }	
  }
  return $data;
}

/**
 * Function to parse the Drupal info files, taken directly from api.drupal.org w/o modification
 */
function drupal_parse_info_file($filename) {

  $info = array();
  if (!file_exists($filename)) {
    return $info;
  }

  $data = file_get_contents($filename);
  if (preg_match_all('
    @^\s*                           # Start at the beginning of a line, ignoring leading whitespace
    ((?:
      [^=;\[\]]|                    # Key names cannot contain equal signs, semi-colons or square brackets,
      \[[^\[\]]*\]                  # unless they are balanced and not nested
    )+?)
    \s*=\s*                         # Key/value pairs are separated by equal signs (ignoring white-space)
    (?:
      ("(?:[^"]|(?<=\\\\)")*")|     # Double-quoted string, which may contain slash-escaped quotes/slashes
      (\'(?:[^\']|(?<=\\\\)\')*\')| # Single-quoted string, which may contain slash-escaped quotes/slashes
      ([^\r\n]*?)                   # Non-quoted string
    )\s*$                           # Stop at the next end of a line, ignoring trailing whitespace
    @msx', $data, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $match) {
      // Fetch the key and value string
      $i = 0;
      foreach (array('key', 'value1', 'value2', 'value3') as $var) {
        $$var = isset($match[++$i]) ? $match[$i] : '';
      }
      $value = stripslashes(substr($value1, 1, -1)) . stripslashes(substr($value2, 1, -1)) . $value3;

      // Parse array syntax
      $keys = preg_split('/\]?\[/', rtrim($key, ']'));
      $last = array_pop($keys);
      $parent = &$info;

      // Create nested arrays
      foreach ($keys as $key) {
        if ($key == '') {
          $key = count($parent);
        }
        if (!isset($parent[$key]) || !is_array($parent[$key])) {
          $parent[$key] = array();
        }
        $parent = &$parent[$key];
      }

      // Handle PHP constants
      if (defined($value)) {
        $value = constant($value);
      }

      // Insert actual value
      if ($last == '') {
        $last = count($parent);
      }
      $parent[$last] = $value;
    }
  }

  return $info;
}

$files = array();

$MODULES = MODULES;

$info_files = array();

  if(isset($MODULES)) $files = rscandir($MODULES);	

  foreach($files as $file) {
	$ext = substr(strrchr($file, '.'), 1);
	if($ext == 'info') {
	      $parse = drupal_parse_info_file($file);
	      if(array_key_exists('project',$parse) && !in_array($parse['project'], $excluded_listing)) {
	        $info_files[$parse['project']] = $parse;
	        if(file_exists(dirname(__FILE__) . '/downloads/' . $parse['project'].'.tar.gz')) {
		      $info_files[$parse['project']]['gz'] = 'downloads/' . $parse['project'].'.tar.gz';
	        } 
	      }		
	}
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Modules | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
</head>
<body>

<div id="wrapper">
	<div id="container">

	<?php echo $VARS_BANNER; ?>

	<!-- content -->
	<div id="content" class="subpage">
		<div id="text">
			<h2 id="modules">Modules</h2>
<p id="module-introduction"><a href="http://drupal.org"><img class="align-left drupal" src="../images/drupal.gif" alt="Drupal" title="Drupal" /></a>Drupal is the platform of choice for LifeDesks. Many of the modules we created or those our friends created are available below for download and are automatically updated every time we release new code. Other modules listed are linked to their project page at <a href="http://drupal.org">http://drupal.org</a>. We will eventually contribute our modules to Drupal's project repository, but we first need your feedback and suggestions. Interested in multisite hosting your own LifeDesk platform? <a href="/files/LifeDesks-Whitepaper.pdf">Read our white paper <img src="../images/page_white_acrobat.png" alt="PDF (200 KB)" /></a> on how we do it.</p>
<p id="disclaimer"><span>Disclaimer:</span> Download and install these modules only in development environments. We need the most help with our very complex classification module.</p>
<p>Our Git repository is: <a href="http://github.com/LifeDesks">http://github.com/LifeDesks</a></p>
<?php

if(!$info_files) {
	echo '<div class="module">We will soon have our modules listed here for download.</div>';
}

$data = '';

foreach($info_files as $module) {
	
  $data .= '<div class="module">' . "\n";
  $data .= '<h3 id="module">'.$module['name'].'</h3>' . "\n";
  $data .= '<div class="module-description">'.$module['description'].'</div>' . "\n";
  $data .= '<div class="module-version"><span>Version:</span> ';
  if(array_key_exists('version',$module)) {
	$data .= $module['version'];
  }
  else {
    $data .= '<em>unknown</em>';	
  }

  if(array_key_exists('datestamp',$module)) $data .= ' '.date('r',$module['datestamp']);
  
  $data .= '</div>' . "\n";
  $data .= '<div class="module-dependencies">' . "\n";
  
  if(array_key_exists('dependencies',$module)) {
	$data .= '<span>Dependencies:</span>' . "\n";
	$data .= '<ul>' . "\n";
	  foreach ($module['dependencies'] as $dependency) {
		$name = array_key_exists($dependency,$info_files) ? $info_files[$dependency]['name'] : $dependency;
        $data .= '<li>'.$name.'</li>' . "\n";
	  }
	$data .= '</ul>' . "\n";
  }
  else {
    $data .= '<em>no dependencies</em>' . "\n";	
  }

  if(array_key_exists('gz',$module) && !array_key_exists($module['project'], $excluded_download)) {
    $data .= '<div class="module-download"><a href="'.$module['gz'].'" onClick="javascript: pageTracker._trackPageview(\'/module/'.$module['project'].'\');">Download '.$module['name'].'</a></div>' . "\n";
  }

  if(array_key_exists($module['project'],$excluded_download)) {
	  if($excluded_download[$module['project']]) {
	    $data .= '<div class="module-contrib"><a href="http://drupal.org/project/'.$module['project'].'">Download</a> at Drupal.org</div>' . "\n";	
	  }	
  }

  $data .= '</div>' . "\n";
	
	
  $data .= '</div>' . "\n";	
}

  echo $data;	

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
<?php echo $VARS_ANALYTICS; ?>
</body>
</html>