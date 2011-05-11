<?php
require_once('../../conf/vars.inc.php');
$url = parse_url(ADMIN_URL);
$domain = explode(".",$url['host'],2);
$host = $domain[1];

$hash = !empty($_GET['q']) && strlen($_GET['q']) == 32 ? $_GET['q'] : '';
if(!$hash) {
  header("HTTP/1.0 404 Not Found");
  header("Location: /error/");
  exit;
}
else {
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Create New Expert Site | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
<script type="text/javascript" src="/js/lifedeskcreate_expert.js"></script>
<script type="text/javascript">jQuery.extend(LifeDesks.settings, {"hostName" : "<?php print $host ?>"});</script>
</head>
<body>

<div id="wrapper">
	<div id="container">

	<?php echo $VARS_BANNER; ?>

			<!-- content -->
			<div id="content" class="subpage">
				
                <div id="text">
	            <h2>We're Making Your LifeDesk</h2>
	            <div id="create_site">
		        <div id="create_site_title"></div>
	            <div id="create_site_message"><p>Your site is being created...</p><img src="/images/ajax-loader-big.gif"><span>(click <a href="/faq/#q02">HERE</a> if more than two minutes has passed)</span></div>
	            <div id="create_site_url"></div>
	            <div id="create_site_help"></div>
	            </div>
	            <form id="create_expert" name="create_expert">
		          <input type="hidden" id="md5" value="<?php print $hash; ?>">
	            </form>
	
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
<?php
}
?>