<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"  "http://www.w3.org/TR/html4/strict.dtd">
<?php
  //This page shows detail of the objects(Photos, Videos, Sounds).
  //オブジェクト(写真、ビデオ、音声)の詳細を表示するページ

  require_once('settings.php');
  require_once(dirname(__FILE__).'/pphoto.php');
  require_once(dirname(__FILE__).'/pvideo.php');
  require_once(dirname(__FILE__).'/psound.php');
  require_once(dirname(__FILE__).'/psns.php');

  $obj;	//Object which show in this page.
  $sns;	//FacebookOGP/Twitter Cards
  $db = new DBconn($db_param);

  function init() {
    global $obj, $db, $sns;

    $db->conn();
    if(isset($_GET['pid'])) {
      //photo
      $obj = new Photo($_GET['pid']);
    } elseif(isset($_GET['vid'])) {
      //video
      $obj = new Video($_GET['vid']);
    } else {
      //sound
      $obj = new Sound($_GET['sid']);
    }

    $sns = new SNS($obj->getTitle(), $obj->getDescription(), $obj->getFileURL());
  }

  function title() {
    global $obj, $psqlAlbum;

    return $obj->getTitle()." - ".$psqlAlbum['AlbumName'];
  }

  init();
?>
<HTML class="detailpage" LANG="<?php print $psqlAlbum['SiteLang']; ?>">
  <HEAD>
<?php
require_once(dirname(__FILE__).'/metatags.php'); //共通の<meta>をファイルからインポート

print $sns->toFacebookOGP('article');
print $sns->toTwitterCards('photo');
?>
    <LINK href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css" rel="stylesheet" type="text/css"></link>
    <TITLE><?php print(title()); ?></TITLE>
    <STYLE type="text/css">
    <!--
      IMG {width: 100%;}
    -->
    </STYLE>
  </HEAD>
  <BODY class="detailpage">
<?php
  print $obj->toHTMLlarge();
?>
  <HR>
  <A href="javascript:history.back();">戻る</A>
  </BODY>
</HTML>
