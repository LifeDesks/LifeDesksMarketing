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
		<div id="container">

	        <?php echo $VARS_BANNER; ?>

			<!-- content -->
			<div id="content" class="subpage">
				
                <div id="text">
                	<h2>About</h2>

<p>LifeDesks are rapidly developed, free biodiversity web environments owned by individuals or teams that desire a place to:</p>
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

<p>LifeDesks are developed by the Center for Library and Informatics at the <a href="http://www.mbl.edu">Marine Biological Laboratory</a>, Woods Hole, Massachusetts.</p>

<h3>LifeDesks Developers</h3>

<p>The LifeDesks team members have backgrounds in the biological and computer sciences and share an <a href="http://en.wikipedia.org/wiki/Agile_software_development">Agile</a> philosophy.</p>
	
<h4>Vitthal Kudal (Biodiversity Informatician)</h4>
<p class="about-bio"><img src="/images/kudal.png" alt="Vitthal Kudal" class="about-mugshots" />Vitthal joined the LifeDesk team from India and has a background in computer sciences. He has expertise in database management and systems architecture and designed the LifeDesks multisite system.</p>

<h4>Lisa Walley (Biodiversity Informatician)</h4>
<p class="about-bio"><img src="/images/walley.jpg" alt="Lisa Walley" class="about-mugshots" />Lisa hails from Wales where she developed <a href="http://www.nhm.ac.uk/research-curation/research/projects/solanaceaesource/">Solanacea Source</a>. She has an interest in human-computer interfaces and strives to improve the usability of LifeDesks.</p>

<h3>Past Members</h3>

<h4>Alexey Shipunov</h4>
<p class="about-bio"><img src="/images/shipunov.jpg" alt="Alexey Shipunov" class="about-mugshots" />Alexey is a botanist, taxonomist, and a developer. He helped the LifeDesks project with deep thinking about names management and wrote the first versions of the classification import tools and the Encyclopedia of Life content partnership exchange.</p>

<h4>David P. Shorthouse</h4>
<p class="about-bio"><img src="/images/shorthouse.jpg" alt="David Shorthouse" class="about-mugshots" />David hails from Canada, has a background in spider ecology. He has an interest in names management and spearheaded the classification editing environment in LifeDesks.</p>
                	
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
<?php echo $VARS_NAVBAR . "\n"; ?>
<?php echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>
