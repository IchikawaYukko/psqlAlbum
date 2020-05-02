<?php
  //URLの引数を見て、適切なphpコードを呼び出す。
  //See URL Parameters and call appropriate code.

  if(isset($_GET['pid']) or isset($_GET['vid']) or isset($_GET['sid'])) {
    // Show object detail
    require_once('idetail.php');
  } elseif(isset($_GET['aid'])) {
    if(isset($_GET['getgpx'])) {
      // return JSON of GPXs
      require_once('gpxjson.php');
    } else {
      // Show Album
      require_once('ialbum.php');
    }
  } elseif(isset($_GET['query'])) {
    // Search
    require_once('isearch.php');
  } elseif(isset($_GET['type'])) {
    require_once('ialbum.php');
  } elseif(isset($_GET['admin'])) {
    // Show Admin page
    require_once('psqlAlbum-admin/iadmin.php');
  } elseif(isset($_GET['login'])) {
    // Show Login page
    require_once('psqlAlbum-admin/ilogin.php');
  } elseif(isset($_GET['logout'])) {
    // Show Logout page
    require_once('psqlAlbum-admin/ilogout.php');
  } elseif(empty($_GET)) {
    // Show album list (top page)
    require_once('iindex.php');
  }