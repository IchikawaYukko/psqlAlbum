<?php
  require_once(dirname(__FILE__).'/pdbconn.php');
  require_once("tool-settings.php");

  $db = new DBconn($db_param);

#  $ignore_dir = array("GPX/", "thumbs/", "video/");
  $gpx_dir = "GPX/";
  $gpx_dbs = array();
  $gpx_files = array();
  $sql = "BEGIN;\n";

  exec("source ./OpenStackAuth.sh;swift list -p $gpx_dir $container", $output);

  foreach ($output as $gpx) {
#    if(!in_array($dir, $ignore_dir, true)) {
      
      array_push($gpx_files, substr($gpx, strlen($gpx_dir)));
#    }
  }

  $db->conn();
  $db->query("SELECT filename FROM gpx", array());
  while($db->hasMoreRows()) {
    $result = $db->nextRow();
    array_push($gpx_dbs, $result["filename"]);
  }

#var_dump($gpx_dbs);
#die('hoge');  
  foreach($gpx_files as $g) {

    $date = substr($g, 0, 4)."-".substr($g, 4, 2)."-".substr($g, 6, 2);
/*    if (strlen($d) === 9) {
      $date_end = $date_begin;
    } else {
      $date_end = substr($d, 0, 4)."-".substr($d, 9, 2)."-".substr($d, 11, 2);
    }
*/
    if(in_array($g, $gpx_dbs, true)) {
      #echo $g." found\n";
    } else {
      #echo $g." gpx-notfound\n";
      $sql.= "INSERT INTO gpx (date, filename) VALUES('$date','$g');\n";
    }
  }
$sql .= "COMMIT;";
echo $sql;
?>
