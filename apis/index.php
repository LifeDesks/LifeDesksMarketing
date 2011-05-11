<?php
require_once('../conf/vars.inc.php');
$host = parse_url(ADMIN_URL);
$domain = explode('.',$host['host'],2);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>APIs and RSS | LifeDesks</title>
<?php echo $VARS_META; ?>
<?php echo $VARS_CSS; ?>
<?php echo $VARS_JAVASCRIPT; ?>
</head>

<body id="home">

<div id="wrapper">
    <div id="container">

        <?php echo $VARS_BANNER; ?>
        
        <!-- content -->
        <div id="content" class="subpage">

                    <div id="text">
                        <h2 id="faq">APIs and RSS Feeds</h2>

                        <div id="description">This is a preliminary list of Application Programming Interfaces (APIs), RSS feeds, and OPML (Outline Processor Markup Language) documents across all LifeDesks.</div>

    
                        <div id="definitions">
                            <dl>
                                <dt>OpenSearch</dt>
                                <dd>An RSS 2.0 <a href="http://www.opensearch.org">OpenSearch</a> feed across all LifeDesks: <span class="api-rss"><em>e.g.</em> <a href="/search/?q=spiders&amp;format=rss"><?php print BASE_URL; ?>/search/?q=spiders&amp;format=rss</a></span><span class="api-rss">OpenSearch Description: <a href="/search/opensearch/"><?php print BASE_URL; ?>/search/opensearch/</a></dd>

                                <dt>MediaRSS (50 items across all LifeDesks)</dt>
                                <dd>An RSS 2.0 MediaRSS feed of the 50 most recent image submissions across all LifeDesks: <span class="api-rss"><a href="/sites/feed/rss.xml"><?php print BASE_URL; ?>/sites/feed/rss.xml</a></span></dd>

                                <dt>Published Checklists RSS</dt>
                                <dd>An RSS 2.0 feed of published checklists across all LifeDesks: <span class="api-rss"><a href="/classifications/feed/rss.xml"><?php print BASE_URL; ?>/classifications/feed/rss.xml</a></span></dd>

                                <dt>MediaRSS OPML</dt>
                                <dd>An XML list of RSS 2.0 MediaRSS feeds containing recently submitted images across all LifeDesks:<span class="api-rss"><a href="opml-images"><?php print BASE_URL; ?>/apis/opml-images/</a></span></dd>

                                <dt>BiblioRSS OPML</dt>
                                <dd>An XML list of RSS 2.0 Biblio RSS feeds containing recently submitted bibliographic items across all LifeDesks:<span class="api-rss"><a href="opml-biblio"><?php print BASE_URL; ?>/apis/opml-biblio/</a></span></dd>

                                <dt>Classification Logs RSS OPML</dt>
                                <dd>An XML list of the classification edit log RSS 2.0 feeds across all LifeDesks: <span class="api-rss"><a href="opml-classification-logs"><?php print BASE_URL; ?>/apis/opml-classification-logs/</a></span></dd>
                                
                                <dt>New Features RSS</dt>
                                <dd>An RSS 2.0 feed of LifeDesks new releases: <span class="api-rss"><a href="/newfeatures/feed/rss.xml"><?php print BASE_URL; ?>/newfeatures/feed/rss.xml</a></span></dd>
                                
                                <dt>Current Tasks RSS</dt>
                                <dd>An RSS 2.0 feed of the tasks we are current working on: <span class="api-rss"><a href="/newfeatures/current/feed/rss.xml"><?php print BASE_URL; ?>/newfeatures/current/feed/rss.xml</a></span></dd>
                                
                                <dt>Site Statistics, including EOL Content Partnership Documents</dt>
                                <dd>A custom XML document containing basic site statistics as well as a link to the Encyclopedia of Life content partnership file as gzipped XML if present: <span class="api-rss"><a href="stats"><?php print BASE_URL; ?>/apis/stats/</a></span></dd>

                                <dt>Basic Site Discovery and Statistics</dt>
                                <dd>JSON-based site-wide discovery with basic statistics and optional callback: <span class="api-rss"><em>e.g.</em> <a href="discover/?taxa=Pardosa,Xysticus&amp;callback=myfunction"><?php print BASE_URL; ?>/apis/discover/?taxa=Pardosa,Xysticus&amp;callback=myfunction</a></span></dd>
                                
                                <dt>OAI-PMH v.2 access to bibliographic data</dt>
                                <dd>Each site has an <a href="http://www.openarchives.org/">Open Archives</a> Initiative Protocol for Metadata Harvesting end-point accessible as: <span class="api-rss"><em>e.g.</em> http://eleodes.lifedesks.org/oai?verb=Identify</span></dd>
                                
                            </dl>
                        </div>
                        
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

<?php echo $VARS_NAVBAR . "\n"; ?>
<?php echo $VARS_ANALYTICS . "\n"; ?>
</body>
</html>