<?php

require_once('conf.inc.php');

$VARS_VERSION = 'getVersion';
$VARS_NAVIGATION = '';

function getVersion() {
  $versionFile = $_SERVER['DOCUMENT_ROOT'] . "/conf/version.txt";
  $fh = @fopen($versionFile, 'r');
  $version = fgets($fh);
  fclose($fh);
  return $version;	
}

$VARS_ANALYTICS_UA = <<< EOF
UA-8484689-1
EOF;

function varsNavigation() {
  $nav[] = array('url' => '/','title' => 'HOME');
  $nav[] = array('url' => '/sites/', 'title' => 'DISCOVER SITES');
  $nav[] = array('url' => '/checklists/', 'title' => 'CHECKLISTS');
  $nav[] = array('url' => '/modules/', 'title' => 'MODULES');
  $nav[] = array('url' => '/apis/', 'title' => 'APIs');
  $nav[] = array('url' => '/about/', 'title' => 'ABOUT');
  $nav[] = array('url' => '/newfeatures/', 'title' => 'WHAT\'S NEW');
  $nav[] = array('url' => '/faq/', 'title' => 'FAQ');
  $nav[] = array('url' => '/contact/', 'title' => 'CONTACT US');
  return $nav;
}

$VARS_META = <<< EOF
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
EOF;

$VARS_CSS = <<< EOF
<link rel="search" href="/search/opensearch/" type="application/opensearchdescription+xml" title="LifeDesks" />
<link href="/favicon.ico" rel="SHORTCUT ICON" type="image/x-icon" />
<link href="/css/screen.css" rel="stylesheet" type="text/css" />
<link href="/css/slider.css" rel="stylesheet" type="text/css" />
EOF;

$VARS_JAVASCRIPT = <<< EOF
<script type="text/javascript" src="/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/js/jcarousellite_1.0.1.pack.js"></script>
<script type="text/javascript" src="/js/lifedeskhome.js"></script>
EOF;

foreach(varsNavigation() as $nav) {
  if($nav['url'] == '/' && $_SERVER['REQUEST_URI'] != "/") {
	$VARS_NAVIGATION .= <<< EOF
<li><a href="{$nav['url']}">{$nav['title']}</a></li>
EOF;
  }
  elseif($nav['url'] != '/' && preg_match(addslashes($nav['url']), $_SERVER['REQUEST_URI']) && !preg_match("/search/", $_SERVER['REQUEST_URI'])){
	echo strpos("?", $_SERVER['REQUEST_URI']);
	$VARS_NAVIGATION .= <<< EOF
<li class="active"><a href="{$nav['url']}">{$nav['title']}</a></li>
EOF;
  }
  elseif($nav['url'] != '/') {
	$VARS_NAVIGATION .= <<< EOF
<li><a href="{$nav['url']}">{$nav['title']}</a></li>
EOF;
  }
}


$VARS_BANNER = <<< EOF
	<!-- header -->
	<div id="header">
	<div id="nav">
	  <ul>
	    {$VARS_NAVIGATION}
	  </ul>
	</div>
		<div id="logo"><h1 class="site-title">LifeDesks</h1><a href="/"><img src="/images/logo.png" alt="" /></a></div>
	</div>
	<!-- /header -->
	<div id="page-separator"></div>
EOF;

$VARS_NAVBAR = <<< EOF
	<!-- green bar -->
	<div id="lifedesk-greenbar-region">
	    <form method="get" action="/search/" id="lifedesk-greenbar-search-form" accept-charset="UTF-8">
		  <div id="lifedesk-greenbar-search" class="lifedesk-greenbar">
	        <input type="text" name="q" size="15" class="lifedesk-greenbar-search-form-text" maxlength="128" />
	        <input type="submit" value="Search" class="lifedesk-greenbar-search-form-submit" />
	      </div>
	      <div id="lifedesk-greenbar-meta" class="lifedesk-greenbar">
		  	<span class="lifedesk-greenbar-create"><a href="/create/">Create a LifeDesk</a></span> | 
		    <span class="lifedesk-greenbar-new"><a href="/newfeatures/">What's New</a> {$VARS_VERSION()}</span> |
		    <span class="lifedesk-greenbar-help"><a href="http://help.lifedesks.org" class="help-lifedesk">Help</a></span>
		  </div>
	    </form>
	</div>
	<!-- /greenbar -->
EOF;

$VARS_TELLFRIEND = <<< EOF
		Know someone who might be interested in learning about LifeDesks?
		<br/>
		
    <div class="tell_friend">
				<form method="post" id="friend_form" action="/sendemail.php" >
             <input type="hidden" name="formType" id="formType" value="sendFriendMail" />
             <label for="my_name">YOUR NAME</label>
             <input type="text" name="my_name" id="my_name" class="input" />
				<label for="my_email">YOUR EMAIL</label>
             <input type="text" name="my_email" id="my_email" class="input" />
             <label for="friends_name">RECIPIENT’S NAME</label>
             <input type="text" name="friends_name" id="friends_name" class="input" /><br />
				<label for="friends_email">RECIPIENT’S EMAIL</label>
             <input type="text" name="friends_email" id="friends_email" class="input" /><br />
             <input type="checkbox" name="include_me" id="include_me" /> Please copy me on this email.<br />
             <div class="submit"><input type="image" src="/images/btn_submit.gif" value="submit"/></div>
             <div class="note">Your privacy is very important to us. We do not rent, sell, or share personal information with other people.</div>
         </form>
     </div>
EOF;

$VARS_FOOTER = <<< EOF
	<div class="clear"></div>
	<div id="footer"><!-- AddThis Button BEGIN -->
	<a class="addthis_button" href="http://www.addthis.com/bookmark.php?v=250&amp;username=xa-4bc921d14e617d03"><img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=xa-4bc921d14e617d03"></script>
	<!-- AddThis Button END -->
	</div>
EOF;

$VARS_ANALYTICS = "";
if(ENVIRONMENT == 'production') {
$VARS_ANALYTICS = <<< EOF
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src=\'" + gaJsHost + "google-analytics.com/ga.js\' type=\'text/javascript\'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("{$VARS_ANALYTICS_UA}");
pageTracker._trackPageview();
</script>
EOF;
}
?>