<?php
  require_once(dirname(__FILE__).'/pdbconn.php');
  require_once("tool-settings.php");

  $db = new DBconn($db_param);

  $ignore_dir = array("GPX/", "thumbs/", "video/");
  $albums = array();
  $dirs = array();
  $sql = "BEGIN;\n";

  exec("source ./OpenStackAuth.sh;swift list -d / $container", $output);

  foreach ($output as $dir) {
    if(!in_array($dir, $ignore_dir, true)) {
      array_push($dirs, $dir);
    }
  }

  $db->conn();
  $db->query("SELECT date_begin FROM album", array());
  while($db->hasMoreRows()) {
    $result = $db->nextRow();
    array_push($albums, $result["date_begin"]);
  }
  
  foreach($dirs as $d) {
    $date_begin = substr($d, 0, 4)."-".substr($d, 4, 2)."-".substr($d, 6, 2);
    if (strlen($d) === 9) {
      $date_end = $date_begin;
    } else {
      $date_end = substr($d, 0, 4)."-".substr($d, 9, 2)."-".substr($d, 11, 2);
    }

    if(in_array($date_begin, $albums, true)) {
      #echo $d." found\n";
    } else {
      #echo $d." album-notfound\n";
      $sql.= "INSERT INTO album (date_begin, date_end, path_photo) VALUES('$date_begin','$date_end','$d');\n";
    }
  }
$sql .= "COMMIT;";
echo $sql;
?>
