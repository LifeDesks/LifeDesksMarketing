<?php
define('XMLFILE', dirname(__FILE__) . '/sites/feed/rss.xml');
require_once('conf/vars.inc.php');

$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);

/**
 *  Return an array of featured LifeDesks
 */
function getFeaturedLifeDesks() {
	$ld[] = array('title' => 'Scarabaeinae dung beetles', 'shortname' => 'scarabaeinae', 'thumbnail' => 'images/featured/sidegallery18.gif');
	$ld[] = array('title' => 'Parmotrema', 'shortname' => 'parmotrema', 'thumbnail' => 'images/featured/sidegallery17.gif');
	$ld[] = array('title' => 'Echinoderms of Panama', 'shortname' => 'echinoderms', 'thumbnail' => 'images/featured/sidegallery16.gif');
    $ld[] = array('title' => 'Arachnids of Central America', 'shortname' => 'arachnids', 'thumbnail' => 'images/featured/sidegallery15.gif');
    $ld[] = array('title' => 'Morpmyridae: African weakly electric fishes', 'shortname' => 'mormyrids', 'thumbnail' => 'images/featured/sidegallery14.gif');
    $ld[] = array('title' => 'Collaboratively Documenting the Biodiversity of Staurozoa', 'shortname' => 'staurozoa', 'thumbnail' => 'images/featured/sidegallery13.gif');
    $ld[] = array('title' => 'Cataloging Diversity in the Sacoglossa', 'shortname' => 'sacoglossa', 'thumbnail' => 'images/featured/sidegallery12.gif');
    $ld[] = array('title' => 'The Proctotrupidae (Hymenoptera) of the World', 'shortname' => 'proctotrupidae', 'thumbnail' => 'images/featured/sidegallery11.gif');
    return $ld; 
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>LifeDesks</title>
<?php echo $VARS_META . "\n"; ?>
<?php echo $VARS_CSS . "\n"; ?>
<?php echo $VARS_JAVASCRIPT . "\n"; ?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
<script>
        var flashvars = {
            feed: "http://www.lifedesks.org/sites/feed/rss.xml",
            backgroundColor: "#FFF"
        };
        var params = {
             allowFullScreen: "true",
             allowscriptaccess: "always"
        };
        swfobject.embedSWF("http://apps.cooliris.com/embed/cooliris.swf",
            "latest-images-cooliris", "630", "195", "9.0.0", "",
            flashvars, params);
    </script>
</head>

<body id="home">

<div id="wrapper">
    <div id="container">

        <?php echo $VARS_BANNER; ?>
        
        <!-- content -->
        <div id="content" class="home">
                <!-- hometop -->
                <div class="hometop">
                    <div id="homedescription">
                        <h1>What are LifeDesks?</h1>
                        <p>LifeDesks are dynamic web environments that make the online management and sharing of biodiversity research easier than ever.</p>
                        
                        <h2>With LifeDesks you can:</h2>
                        <div id="homeicons">
                            <div>
                                <a href="checklists"><img src="images/cta1.gif" alt="share your checklist" /></a>
                                <p>Upload, manage and share your checklist</p>
                            </div>
                            <div>
                                <img src="images/cta2.gif" alt="build a team" />
                                <p>Build a team of collaborators</p>
                            </div>
                            <div>
                                <img src="images/cta3.gif" alt="organize your content" />
                                <p>Organize your content</p>
                            </div>
                            <div>
                                <a href="http://www.eol.org" rel="nofollow" target="_blank"><img src="images/cta4.gif" alt="participate in EOL" /></a>
                                <p>Share with the Encyclopedia of Life</p>
                            </div>
                        </div>
                        <div style="clear:both"></div>
                    </div>
                    <img src="/images/bluebox_bottom_bg.gif" alt="" />
                </div>
                <!-- /hometop -->

<div id="latest-images">
<h2>Latest Images</h2>
<div id="latest-images-cooliris"></div>
</div>
<div class="clear"></div>

            </div><!-- /content -->

            <div id="sidebar">
                <div class="sidebanner"><span>MAKE A LIFEDESK</span></div>
                <div class="sidecontent">
                    <p><em>LifeDesks are superb platforms for taxonomists to engage the public in their science. Their efforts may also be shared with the wider scientific community through existing and emerging industry standards. The LifeDesk team is devoted to making this happen.</em></p>
                    <div class="btn1"><a href="create/">CREATE</a></div>
                </div><!-- /sidecontent -->

                <div class="explorebanner"><span>Featured LifeDesks</span></div>
                <div class="explore">
                    <div class="ex_arrowl"><a class="side-prev" href="#"><img src="images/arrow2_left.gif" alt="" /></a></div>
                    <div class="sidegallery">
                        <ul>
<?php foreach (getFeaturedLifeDesks() as $site): ?>
                            <li><a href="http://<?php print $site['shortname'].'.'.$domain[1]; ?>"><img src="<?php print $site['thumbnail']; ?>" alt="<?php print $site['title']; ?>" width="189" height="180" /></a></li>
<?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="ex_arrowl"><a class="side-next" href="#"><img src="images/arrow2_right.gif" alt="" /></a></div>
                </div>
            </div><!-- /sidebar --> 

    <?php echo $VARS_FOOTER . "\n"; ?>
    </div><!-- /container -->
</div><!-- /wrapper -->
<?php echo $VARS_NAVBAR . "\n"; ?>
<?php echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>
