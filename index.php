<?php
  //require_once('settings.php');

  if(isset($_GET['pid']) or isset($_GET['vid']) or isset($_GET['sid'])) {
    require_once('idetail.php');
  } elseif(isset($_GET['aid']) == TRUE) {
    $_GET['id'] = $_GET['aid'];
    require_once('ialbum.php');
  } elseif(isset($_GET['query']) == TRUE) {
    require_once('isearch.php');
  } else {
    require_once('iindex.php');
  }
?>
