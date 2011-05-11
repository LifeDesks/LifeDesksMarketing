<?php
/*
 * Copyright (c) 2006-2008 Byrne Reese. All rights reserved.
 * 
 * This library is free software; you can redistribute it and/or modify it 
 * under the terms of the BSD License.
 *
 * This library is distributed in the hope that it will be useful, but 
 * WITHOUT ANY WARRANTY; without even the implied warranty of 
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. 
 *
 * @author Byrne Reese <byrne@majordojo.com>
 * @version 1.01
 */

class Paginator {
  function paginate($offset,$total,$limit,$base = '')
  {
    $lastp = ceil($total / $limit);
    $thisp = ceil(($offset == 0 ? 1 : ($lastp / ($total / $offset))));
    print "    <div class=\"paginator\">\n";
    if ($thisp==1) { print "      <SPAN CLASS=\"atstart\">&lt Prev</SPAN>\n"; }
    else { print "      <a href=\"".$base.((($thisp - 2) * $limit) + 1)."\" class=\"prev\">&lt; Prev</a> \n"; }
    $page1 = $base . "1";
    $page2 = $base . ($limit + 1);
    if ($thisp <= 5) {
      for ($p = 1;$p <= min( ($thisp<=3) ? 5 : $thisp+2,$lastp); $p++) {
	if ($p == $thisp) {
	  print "      <span class=\"this-page\">$p</span>\n ";
	} else {
	  $url = $base . (($limit * ($p - 1)) + 1);
	  print "      <a href=\"$url\">$p</a>\n ";
	}
      }
      if ($lastp > $p) {
	print "      <span class=\"break\">...</span>\n";
	print "      <a href=\"".$base.((($lastp - 1)* $limit)+1)."\">".($lastp-1)."</a>\n";
	print "      <a href=\"".$base.(($lastp*$limit)+1)."\">".$lastp."</a>\n";
      }
    }
    else if ($thisp > 5) {
      print "      <a href=\"".$page1."\">1</a> <a href=\"".$page2."\">2</a>";
      if ($thisp != 6) { print " <span class=\"break\">...</span>\n "; }
      for ($p = ($thisp == 6) ? 3 : min($thisp - 2,$lastp-4);$p <= (($lastp-$thisp<=5) ? $lastp:$thisp+2); $p++) {
	if ($p == $thisp) {
	  print "      <span class=\"this-page\">$p</span>\n ";
	} else if ($p <=$lastp) {
	  $url = $base . (($limit * ($p - 1)) + 1);
	  print "      <a href=\"$url\">$p</a>\n ";
	}
      }
      if ($lastp > $p+1) {
	print "      <span class=\"break\">...</span>\n";
	print "      <a href=\"".$base.((($lastp - 1)* $limit)+1)."\">".($lastp-1)."</a>\n";
	print "      <a href=\"".$base.(($lastp*$limit)+1)."\">".$lastp."</a>\n";
      }
    }
    if ($thisp == $lastp) { print "      <SPAN CLASS=\"atend\"> Next &gt</SPAN>\n"; }
    else { print "      <a href=\"".$base.((($thisp + 0) * $limit) + 1)."\" class=\"next\">Next &gt;</a>\n"; }
    print "    </div>\n";
  }
}
?>
