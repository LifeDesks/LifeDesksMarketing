<?php
require_once('../../conf/vars.inc.php');
$iteration = array();
$current_tasks = @implode("", file(ADMIN_URL . "/lifedesk-current"));
if($current_tasks) $iteration = json_decode($current_tasks);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Current Tasks | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
<link rel="alternate" type="application/rss+xml" title="LifeDesks Current Iteration" href="feed/rss.xml" />
</head>

<body id="home">

<div id="wrapper">
	<div id="container">

        <?php echo $VARS_BANNER; ?>
		
		<!-- content -->
		<div id="content" class="subpage">
			<div id="text">
				<h2>Current Tasks</h2>

				<p>Our development iterations are two weeks long and we typically deploy every second Wednesday. Iteration meetings are every second Thursday and we choose tasks dependent on demand and estimates of time to completion. Users contribute requests via a "Feedback" tab in their LifeDesks.</p>

				<?php
				if($iteration && is_object($iteration)) {
					foreach($iteration->tasks as $iteration) {
						if($iteration->current_version) {
							echo "<div class='release_version'>" . "\n";
							echo "<h3>" . $iteration->current_version . " (expected release: " . $iteration->expected_release_date . ")</h3>" . "\n";
							echo "<ul>" . "\n";
						  	if($iteration->tickets) {
					 			foreach($iteration->tickets as $ticket) {
							    	echo "<li>" . $ticket->value . "</li>" . "\n";
							  	}
							}
							echo "</ul>" . "\n";
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
			</div>
			
		</div><!-- /sidebar -->
		
	<?php echo $VARS_FOOTER; ?>
	</div><!-- /container -->
</div><!-- /wrapper -->

<?php echo $VARS_NAVBAR; ?>
<?php echo $VARS_ANALYTICS; ?>
</body>
</html>