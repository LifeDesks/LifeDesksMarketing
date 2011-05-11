<?php
require_once('../../conf/conf.inc.php');
header('Content-Type: application/xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
<ShortName>LifeDesks</ShortName>
<Description>Search across all LifeDesks</Description>
<Contact>feedback@lifedesks.org</Contact>
<Url type="text/html" template="<?php echo BASE_URL; ?>/search/?q={searchTerms}&amp;start={startIndex?}"></Url>
<Url type="application/rss+xml" template="<?php echo BASE_URL; ?>/search/?q={searchTerms}&amp;start={startIndex?}&amp;format=rss"></Url>
<LongName>Search across all LifeDesks</LongName>
<SyndicationRight>open</SyndicationRight>
<AdultContent>0</AdultContent>
<Language></Language>
<OutputEncoding>UTF-8</OutputEncoding>
<Image height="16" width="16" type="image/vnd.microsoft.icon"><?php echo BASE_URL; ?>/favicon.ico</Image>
</OpenSearchDescription>