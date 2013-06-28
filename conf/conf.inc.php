<?php
//set database connection properties
define('USERNAME','lifedesk');
define('PASSWORD', 'lifedesk');
define('DB_SERVER', '127.0.0.1');
define('DB_NAME', 'lifedesk_production');
define('MYSQL_BIN_PATH','/usr/local/bin/mysql'); // /data/mysql/bin/mysql on integration and production

define('SMTP_SERVER', 'localhost');
define('SMTP_PORT', 465);
define('SMTP_USERNAME', 'lifedesks');
define('SMTP_PASSWORD', 'lifedesks');
define('SMTP_FROM', 'example@example.exa');
define('SMTP_FEEDBACK', 'example@example.exa');

//define some other vars
define('ENVIRONMENT', 'development');
define('BASE_URL','http://www.lifedesks_marketing.org');
define('ADMIN_URL','http://admin.lifedesks.org');
define('CACHE', '/data/cache');
define('MODULES', '/data/www/modules/');

//define RSS feed files
define('SITES_RSS', '/data/www/sites/feed/rss.xml');
define('CLASSIFICATIONS_RSS', '/data/www/classifications/feed/rss.xml');
define('NEWFEATURES_RSS', '/data/www/newfeatures/feed/rss.xml');
define('CURRENTTASKS_RSS', '/data/www/newfeatures/current/feed/rss.xml');

define('SOLR_SERVER', 'http://10.19.19.19:8080/solr');
define('SOLR_FILE_DELIMITER', '|');
define('SOLR_MULTI_VALUE_DELIMETER', ';');
define('SOLR_TIMEOUT', 5);
define('OUTFILE', '/data/www/files/lifedesk_taxa.text');
define('CSVPATH', '/data/www/files/solr_csv.text');

//set the default timezone
date_default_timezone_set('America/New_York');
?>
