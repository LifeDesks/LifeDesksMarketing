<?php
require_once('../conf/vars.inc.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Frequently Asked Questions | LifeDesks</title>
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
                	<h2 id="faq">FAQ</h2>
               	
                  <ul id="faqNav">
                    <li><a href="#q01">Is LifeDesk for me?</a></li>
                    <li><a href="#q02">Are there any browser requirements?</a></li>
                    <li><a href="#q03">May I have my own domain name?</a></li>
                    <li><a href="#q04">What if I'm not a professional taxonomist?</a></li>
                    <li><a href="#q05">What is the software behind LifeDesks?</a></li>
                    <li><a href="#q06">I'm a developer, how can I help?</a></li>
                    <li><a href="#q07">Who helped develop LifeDesks?</a></li>
                    <li><a href="#q08">May I obtain the software and host my own LifeDesk?</a></li>
                    <li><a href="#q09">How often are bug fixes released?</a></li>
                  </ul>

                  <div id="definitions">
                    <dl>
                      <dt><a name="q01" id="q01"></a>Is LifeDesk for me?</dt>
                      <dd>LifeDesks is intended for teams of taxonomists. This is very intense work so there is very little time left to develop software or maintain collaborative environments. However, tools like LifeDesks will help accelerate their science because they may solicit help from members of the public who desire joining their team.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q02" id="q02"></a>Are there any browser requirements?</dt>
                      <dd>LifeDesks make heavy use of JavaScript, especially in the classification editor, which is a dynamic environment with many activities. We recommend that you use a modern browser like Internet Explorer 8, Firefox 3, Safari 4, or Google Chrome.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q03" id="q03"></a>May I have my own domain name?</dt>
                      <dd>This is technically possible and something we have certainly been thinking about. However, there are costs to registering a domain name. We know this will be in demand and it is on our list of things to do, so please bear with us should this be a reason for holding back.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q04" id="q04"></a>What if I'm not a professional taxonomist?</dt>
                      <dd>Chances are, you have something very valuable to contribute and we recognize your enthusiasm. If a LifeDesk does not yet exist for your favorite group of taxa, please spread the word. You are welcome to solicit membership in any LifeDesk.
						<br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q05" id="q05"></a>What is the software behind LifeDesks?</dt>
                      <dd>We use <a href="http://drupal.org">Drupal</a>, an open source content management platform. We really like it because of the amazing fanbase, rich array of contributed modules, and its robust handling of taxonomy. As you can imagine, we spent a lot of time pushing taxonomy in Drupal to its limits.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q06" id="q06"></a>I'm a developer, how can I help?</dt>
                      <dd>We're always looking for fresh ideas. Please use our <a href="/contact/">Contact Us</a> page. Our Subversion repository is accessible at <a href="http://svn.lifedesks.org">http://svn.lifedesks.org</a>.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q07" id="q07"></a>Who helped develop LifeDesks?</dt>
                      <dd>We hosted a very successful Drupal Taxonomy Sprint September 8-11, 2008 at the <a href="http://synthesis.eol.org/">Biodiversity Synthesis Center</a>, Field Museum, Chicago, Illinois. We dug deep into how to best manage names (terms) and data (metadata, relationships) about names. You can read more about our activities at the Sprint and the progress being made since then on the <a href="http://groups.drupal.org/node/14749">Drupal Group</a>. We also received help from <a href="http://www.oho.com/">OHO</a> and <a href="http://www.jumpingjackrabbit.com/">Jackrabbit</a> who worked on these pages.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q08" id="q08"></a>May I obtain the software and host my own LifeDesk?</dt>
                      <dd>Absolutely! Here is our <a href="/modules">list of modules</a> to download. We also have a whitepaper on how we execute our multisite hosted environment and how to use our modules available <a href="/files/LifeDesks-Whitepaper.pdf">here <img src="../images/page_white_acrobat.png" alt="PDF (200 KB)"></a>. Our Subversion repository is accessible at <a href="http://svn.lifedesks.org">http://svn.lifedesks.org</a>.
                        <br />
                        <a href="#faq">Back to top</a></dd>
                      <dt><a name="q09" id="q09"></a>How often are bug fixes released?</dt>
                      <dd>Starting August 1, 2010, in addition to supporting LifeDesk, some members of the LifeDesk team will be working with the Encyclopedia of Life (EOL) team to make it easier to contribute content directly to EOL. While we are working on this project we will be fixing bugs and releasing fixes as needed. Enhancement releases are typically released every second Wednesday of the month at 10AM EST. Sometimes we have to take all sites offline if changes to database structure are required. 
                        <br />
                        <a href="#faq">Back to top</a></dd>
                    </dl>
                  </div>
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
