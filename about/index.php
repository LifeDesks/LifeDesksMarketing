<?php
require_once('../conf/vars.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>About | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
</head>
<body id="wrap">
  <div id="wrapper">
    <div id="container"><?php echo $VARS_BANNER; ?>
      <div id="content" class="subpage">
        <div id="text">
          <h2>About</h2>
          <p>LifeDesks are free biodiversity web environments owned by individuals or teams that desire a place to:</p>
          <ul class="about-list">
            <li>Build species pages</li>
            <li>Build a consensus-based classification</li>
            <li>Gain personal and institutional visibility</li>
            <li>Have a simple, task-oriented work flow</li>
            <li>Have an environment to share the work</li>
            <li>Partner with the Encyclopedia of Life</li>
          </ul>
          <p>...and a flexible platform that provides:</p>
          <ul class="about-list">
            <li>Import, export, and back-up tools</li>
            <li>Granular control over membership and permissions</li>
            <li>Zero responsibility to develop and maintain code</li>
            <li>Freedom from server and infrastructure management</li>
          </ul>
          <p>LifeDesks are maintained by the <a href="http://www.mbl.edu">Marine Biological Laboratory</a>, Woods Hole, Massachusetts.</p>
        </div>
        <img src="/images/bluebox_bottom_bg.gif" alt="" />
      </div>
      <div id="sidebar">
        <div class="sidebanner"><span>Tell a friend about LifeDesks</span></div>
        <div class="sidecontent"><?php echo $VARS_TELLFRIEND; ?></div>
      </div>
    <?php echo $VARS_FOOTER; ?>
    </div>
  </div>
<?php echo $VARS_NAVBAR . "\n"; ?>
<?php echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>
