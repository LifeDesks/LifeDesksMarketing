<?php
require_once('../../conf/conf.inc.php');
$subd = isset($_GET['url']) ? $_GET['url'] : "";
if($subd) {
   print(@implode("", file(ADMIN_URL . "/check_sitename/" . $subd)));
}
?>