<?php
  require_once('settings.php');

  if(isset($_GET['pid']) == TRUE or isset($_GET['vid']) == TRUE) {
    require_once(dirname(__FILE__)."/".$psqlAlbum['AlbumLibDir'].'idetail.php');
  } elseif(isset($_GET['aid']) == TRUE) {
    $_GET['id'] = $_GET['aid'];
    require_once(dirname(__FILE__)."/".$psqlAlbum['AlbumLibDir'].'ialbum.php');
  } elseif(isset($_GET['query']) == TRUE) {
    require_once(dirname(__FILE__)."/".$psqlAlbum['AlbumLibDir'].'isearch.php');
  } else {
    require_once(dirname(__FILE__)."/".$psqlAlbum['AlbumLibDir'].'iindex.php');
  }
?>
