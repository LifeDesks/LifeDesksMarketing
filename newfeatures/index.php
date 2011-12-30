<?php
require_once('../conf/vars.inc.php');
$releases = array();
$release_data = @implode("", file(ADMIN_URL . "/lifedesk-releases"));
if($release_data) $releases = json_decode($release_data);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <title>What's New | LifeDesks</title>
    <?php echo $VARS_META; ?>
    <?php echo $VARS_CSS; ?>
    <?php echo $VARS_JAVASCRIPT; ?>
    <link rel="alternate" type="application/rss+xml" title="LifeDesks New Features" href="feed/rss.xml" />
  </head>

  <body>
    <div id="wrapper">
      <div id="container">
        <?php echo $VARS_BANNER; ?>

        <!-- content -->
        <div id="content" class="subpage">
          <div id="text">
            <h2>What's New?</h2>
            <div class="announcement">New development on LifeDesks officially ceased August 1, 2010. Bugs are fixed as needed.</div>
            <?php 
              $admin_is_accessible = file(ADMIN_URL . "/lifedesk-releases");
              if ($admin_is_accessible === FALSE) {
                ?>
                <div class="error">Sorry, new features and recent bug fixes information is unavailable at this time due to technical issues, please try again later.</div>
                <?php
              }

              if($releases && is_object($releases)) {
                foreach($releases->releases as $release) {
                  if($release->version) {
                    echo "<div class='release_version'>" . "\n";
                    echo "<h3>" . $release->version . " (" . $release->release_date . ")</h3>" . "\n";
                    if($release->bug_fixes && $release->bug_fixes[0]->value) {
                      echo "<h4>Bug Fixes</h4>" . "\n";
                      echo "<ul>" . "\n";
                      foreach($release->bug_fixes as $bug_fix) {
                        echo "<li>" . $bug_fix->value . "</li>" . "\n";
                      }
                      echo "</ul>" . "\n";	
                    }
                    if($release->new_features && $release->new_features[0]->value) {
                      echo "<h4>New Features</h4>" . "\n";
                      echo "<ul>" . "\n";
                      foreach($release->new_features as $new_feature) {
                        echo "<li>" . $new_feature->value . "</li>" . "\n";
                      }
                      echo "</ul>" . "\n";	
                    }
                    echo "</div>" . "\n";
                  }
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
    <?php echo $VARS_ANALYTICS; ?>
  </body>
</html>
