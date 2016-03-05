<?php
  //URLの引数を見て、適切なphpコードを呼び出す。

  if(isset($_GET['pid']) or isset($_GET['vid']) or isset($_GET['sid'])) {
    //オブジェクト詳細表示
    require_once('idetail.php');
  } elseif(isset($_GET['aid']) == TRUE) {
    //アルバム
    $_GET['id'] = $_GET['aid'];
    require_once('ialbum.php');
  } elseif(isset($_GET['query']) == TRUE) {
    //検索
    require_once('isearch.php');
  } else {
    //アルバム一覧(トップページ)
    require_once('iindex.php');
  }
?>
