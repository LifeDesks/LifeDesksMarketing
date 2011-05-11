<?php
require_once('../../../conf/conf.inc.php');
require_once('../../../conf/db.class.php');

$POST_URL = ADMIN_URL . "/node/add/drupal-site";

header('Content-Type: text/plain; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0, max-age=0');
header('Pragma: no-cache');
header('Expires: Thu, 01 Jan 1970 00:00:00 GMT');


$hash = isset($_GET['q']) && strlen($_GET['q']) == 32 ? $_GET['q'] : '';

$output = '{ ';

if($hash) {

  $db = new Database(DB_SERVER, USERNAME, PASSWORD, DB_NAME);
  $row = $db->query_first("SELECT * FROM request_experts WHERE md5 = '" . $db->escape($hash) . "'");

  if(!$row) {
    $output.= '"create_status" : -1';
  }
  else {
	  $status = $row['status'];

      switch($status) {
	    case 0:
	     $output.= '"create_status" : ' . $status . ', "title" : "' . addslashes($row['title']) . '", "givenname" : "' . addslashes($row['givenname']) . '", "surname" : "' . addslashes($row['surname']) . '"';
	     $fields = array(
	       'form_id' => 'drupal_site_node_form',
	       'title' => $row['title'],
	       'shortname' => $row['url_requested'],
	       'givenname' => $row['givenname'],
	       'surname' => $row['surname'],
	       'mail' => $row['email'],
	       'username' => $row['username'],
	       'password' => $row['password'],
	       'profile' => 'expert',
	       'md5' => mysql_real_escape_string($hash),
	       'op' => 'Create LifeDesk',
	     );
	     $data = http_build_query($fields);
         do_post_request($POST_URL,$data,'POST','',false,1);
	     break;
	    case 1:
          $output.= '"create_status" : ' . $status . ', "title" : "' . addslashes($row['title']) . '", "givenname" : "' . addslashes($row['givenname']) . '", "surname" : "' . addslashes($row['surname']) . '"';
	      break;
	    case 2:
	      $output.= '"create_status" : ' . $status . ', "title" : "' . addslashes($row['title']) . '", "url" : "' . $row['url_requested'] . '", "givenname" : "' . addslashes($row['givenname']) . '", "surname" : "' . addslashes($row['surname']) . '", "username" : "' . addslashes($row['username']) . '"';
	      break;
        default:
          $output.= '"create_status" : -1';
      }
  }

}

else {
 $output.= '"create_status" : -1';	
}

$output.= ' }';

print $output;

function do_post_request($url, $data, $method = 'POST', $optional_headers = null,$getresponse = false,$timeout=80) {
      $params = array('http' => array(
                   'method' => $method,
                   'content' => $data
                ));
      if ($optional_headers !== null) {
         $params['http']['header'] = $optional_headers;
      }
      $ctx = stream_context_create($params);
      $fp = @fopen($url, 'rb', false, $ctx);
      if (!$fp) {
        return false;
      }
      else {
	      stream_set_timeout($fp, $timeout);
	      if ($getresponse){
	        $response = stream_get_contents($fp);
	        return $response;
	      }
          else {
	        return true;
          }
          @fclose($fp);
      }
}

?>