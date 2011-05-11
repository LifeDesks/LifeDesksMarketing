<?php
require_once('../conf/vars.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Error | LifeDesks</title>
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
                	<h2>Sorry</h2>
We're sorry, the page you are looking for cannot be <?php echo "found";?>.
                </div>
				<img src="<?php echo BASE_URL; ?>/images/bluebox_bottom_bg.gif" alt="" /><br  /><br />
				
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