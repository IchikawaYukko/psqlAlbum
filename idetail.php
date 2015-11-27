<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"  "http://www.w3.org/TR/html4/strict.dtd">
<?php
  //This page shows detail of the objects(Photos, Videos, Sounds).
  //オブジェクト(写真、ビデオ、音声)の詳細を表示するページ

  require_once('settings.php');
  require_once(dirname(__FILE__).'/pphoto.php');
  require_once(dirname(__FILE__).'/pvideo.php');

  $obj;	//Object which show in this page.
  $db = new DBconn($db_param);

  function init() {
    global $obj, $db;

    $db->conn();
    if(isset($_GET['pid'])) {
      //photo
      $obj = new Photo($_GET['pid']);
    } else {
      //video
      $obj = new Video($_GET['vid']);
    }
  }

  function title() {
    global $obj, $psqlAlbum;

    return $obj->getTitle()." - ".$psqlAlbum['AlbumName'];
  }

  init();
?>
<HTML LANG="<?php print $psqlAlbum['SiteLang']; ?>">
  <HEAD>
    <?php require_once(dirname(__FILE__).'/metatags.php'); //共通の<meta>をファイルからインポート ?>
    <!-- Facebook OGP -->
    <meta property="og:title" content="<?php print $obj->getTitle(); ?>" />
    <meta property="og:type" content="article" />
    <meta property="og:description" content="<?php print $obj->getDescription(); ?>" />
    <meta property="og:url" content="<?php print $psqlAlbum['AlbumRoot']."index.php?pid=".$_GET['pid']; ?>" />
    <meta property="og:image" content="<?php print $psqlAlbum['AlbumRoot'].$obj->getFilename(); ?>" />
    <meta property="og:site_name" content="<?php print $psqlAlbum['AlbumName']; ?>" />
    <meta property="og:locale" content="ja_JP" />
    <!-- Twitter Card -->
    <meta name="twitter:card" content="photo" />
    <meta name="twitter:site" content="<?php print $psqlAlbum['AuthorTwitterAccount']; ?>" />
    <meta name="twitter:title" content="<?php print($obj->getTitle()); ?>" />
    <meta name="twitter:image" content="<?php print $psqlAlbum['AlbumRoot'].$obj->getFilename(); ?>" />
    <meta name="twitter:url" content="<?php print($psqlAlbum['AlbumRoot']."index.php?pid=".$_GET['pid']); ?>" />
    <LINK href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css" rel="stylesheet" type="text/css"></link>
    <TITLE><?php print(title()); ?></TITLE>
    <STYLE type="text/css">
    <!--
      IMG {width: 100%;}
    -->
    </STYLE>
  </HEAD>
  <BODY>
<?php
  print $obj->toHTMLlarge();
?>
  <HR>
  <A href="javascript:history.back();">戻る</A>
  </BODY>
</HTML>