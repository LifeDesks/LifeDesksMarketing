<?php
header('HTTP/1.1 503 Service Temporarily Unavailable',true,503);
header('Status: 503 Service Temporarily Unavailable');
header('Retry-After: 86400');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="robots" content="noindex, nofollow" />
<title>Maintenance | LifeDesks</title>
<link href="/css/screen.css" rel="stylesheet" type="text/css" />
</head>
<body>

<div id="wrapper">
	<div id="container">

	<!-- header -->
	<div id="header">
		<div id="logo"><a href="/"><h1 class="site-title">LifeDesks</h1><img src="/images/logo.png" alt="" /></a></div>
	</div>
	<!-- /header -->
	<div id="page-separator"></div>

			<div id="content" class="subpage">
				
                <div id="text">
                	<h2>Under Maintenance</h2>
We're sorry, LifeDesks are currently undergoing routine maintenance. We will be back online soon.
                </div>
				<img src="/images/bluebox_bottom_bg.gif" alt="" />
				
			</div><!-- /content -->
			
			<div id="sidebar">
			</div><!-- /sidebar -->

			<div class="clear"></div>
			<div id="footer"></div>
			</div><!-- /container -->

		</div><!-- /wrapper -->
			<!-- green bar -->
			<div id="lifedesk-greenbar-region">
			    <form method="get" action="/search/" id="lifedesk-greenbar-search-form" accept-charset="UTF-8">
				  <div id="lifedesk-greenbar-search" class="lifedesk-greenbar">
			        <input type="text" name="q" size="15" class="lifedesk-greenbar-search-form-text" maxlength="128" disabled />
			        <input type="submit" value="Search" class="lifedesk-greenbar-search-form-submit" disabled />
			      </div>
			      <div id="lifedesk-greenbar-meta" class="lifedesk-greenbar">
				  </div>
			    </form>

			</div>
			<!-- /greenbar -->
</body>
</html>

