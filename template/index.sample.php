<?php
include('../conf/vars.inc.php');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
</head>

<body id="home">

<div id="wrapper">
	<div id="container">

        <?php echo $VARS_BANNER; ?>
		
		<!-- content -->
		<div id="content" class="home">
		
		</div><!-- /content -->
		
		<div id="sidebar">
			<div class="sidebanner"><span>Tell a friend about LifeDesks</span></div>
			<div class="sidecontent">
			<?php echo $VARS_TELLFRIEND; ?>
			</div>
			
		</div><!-- /sidebar -->
		
	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->

<?php echo $VARS_NAVBAR; ?>
<?php echo $VARS_ANALYTICS; ?>
</body>
</html>	