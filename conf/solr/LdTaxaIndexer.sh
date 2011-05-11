#!/usr/local/bin/php
<?php
require_once (dirname(__FILE__) . '/LdTaxaIndexer.php');
$lti = new LDTaxaIndexer();
$lti->index();
?>
