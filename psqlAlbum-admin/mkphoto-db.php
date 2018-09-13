<?php
  require_once(dirname(__FILE__).'/pdbconn.php');
  require_once("tool-settings.php");

  $db = new DBconn($db_param);

# $ignore_dir = array("GPX/", "thumbs/", "video/");
# $gpx_dir = "GPX/";
  $photo_dbs = array();
  $photo_files = array();
  $sql = "BEGIN;\n";

  exec("source ./OpenStackAuth.sh;swift list -p 20 $container", $output);

  foreach ($output as $photo) {
#    if(!in_array($dir, $ignore_dir, true)) {
      
      array_push($photo_files, substr($photo, strpos($photo, '/') +1));
#    }
  }

  $db->conn();
  $db->query("SELECT filename FROM photo", array());
  while($db->hasMoreRows()) {
    $result = $db->nextRow();
    array_push($photo_dbs, $result["filename"]);
  }

  foreach($photo_files as $p) {

/*    $date = substr($p, 0, 4)."-".substr($p, 4, 2)."-".substr($p, 6, 2);
    if (strlen($d) === 9) {
      $date_end = $date_begin;
    } else {
      $date_end = substr($d, 0, 4)."-".substr($d, 9, 2)."-".substr($d, 11, 2);
    }
*/
    if(in_array($p, $photo_dbs, true)) {
      #echo $p." found\n";
    } else {
      #echo $p." photo-notfound\n";
      $date = substr($p, 4, 4)."-".substr($p, 8, 2)."-".substr($p, 10, 2);;
      $sql.= "INSERT INTO photo (filename, datetaken, flag, orientation) VALUES('$p', '$date', NULL, 1);\n";
    }
  }
$sql .= "COMMIT;";
echo $sql;
?>
