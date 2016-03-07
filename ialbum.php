<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
  require_once('settings.php');
  require_once(dirname(__FILE__).'/palbum.php');
  require_once(dirname(__FILE__).'/pphoto.php');
  require_once(dirname(__FILE__).'/pvideo.php');
  require_once(dirname(__FILE__).'/psound.php');
  require_once(dirname(__FILE__).'/pgpx.php');
  require_once(dirname(__FILE__).'/psns.php');

  $album; $photo; $video; $sound; $gpx; $sns;
  $db = new DBconn($db_param);

  function init() {
    global $psqlAlbum, $photo, $video, $sound, $album, $gpx, $db, $sns;

    $db->conn();
    if(isset($_GET['type'])) {
      if($_GET['type'] == 'video_album') {
        try {
          $video = Video::getObjectsBySearchQuery("");
        } catch(Exception $e) {
        }
        $sns = new SNS("ビデオアルバム", $psqlAlbum['Description'], NULL);
      }
    } else {
      $album = new Album($_GET['id']);

      try {
        $photo = Photo::getObjectsInDateRange($album->getDatebegin(), $album->getDateend());
        $sns = new SNS(title(), $psqlAlbum['Description'], $photo[0]->getFileURL());
      } catch (Exception $e) {
        $sns = new SNS(title(), $psqlAlbum['Description'], NULL);
      }
      try {
        $video = Video::getObjectsInDateRange($album->getDatebegin(), $album->getDateend());
      } catch(Exception $e) {
      }
      try {
        $sound = Sound::getObjectsInDateRange($album->getDatebegin(), $album->getDateend());
      } catch(exception $e) {
      }

      $gpx = GPX::getGPXsInDateRange($album->getDatebegin(), $album->getDateend());
    }
  }

  function title() {
    global $album, $psqlAlbum;

    return $album->getTitle()." - ".$psqlAlbum['AlbumName'];
  }

  init();
?>
<HTML LANG="<?php print $psqlAlbum['SiteLang']; ?>">
	<HEAD>
<?php
require_once(dirname(__FILE__).'/metatags.php'); //共通の<meta>をファイルからインポート

print $sns->toFacebookOGP('article');
print $sns->toTwitterCards('photo');
?>
		<LINK rel="stylesheet" type="text/css" href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css">
		<link rel="canonical" href="<?php print $psqlAlbum['AlbumRoot'].'index.php?aid='.$_GET['id']; ?>">
		<SCRIPT language="JavaScript" src="<?php print $psqlAlbum['LibDir']; ?>OpenLayers.js"></SCRIPT>
		<SCRIPT language="JavaScript" src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></SCRIPT>
		<SCRIPT language="JavaScript" src="<?php print $psqlAlbum['AlbumLibDir']; ?>map.js"></SCRIPT>
		<SCRIPT language="JavaScript"><!--
		  var gpx = <?php print GPX::arrayToJSON($gpx); ?>
		// --></SCRIPT>
		<TITLE><?php print (title()); ?></TITLE>
	</HEAD>
	<BODY onload="mapinit(gpx);">
		<H1><?php print $album->getTitle(); ?></H1>
		<P><STRONG>概要：</STRONG><?php print ($album->getDescription()); ?><BR><BR></P>
		<DIV ID="canvas"></DIV>
		<P>行程図。マウスドラッグで地図の移動、ホイールで拡大/縮小ができます。<BR>
		(軌跡データが表示されるまで、しばらく時間がかかります。)<BR><BR>
		GPSデータダウンロード<A href=../usingGPX.html>「GPSデータとは？」</A><BR>
<?php
foreach($gpx as $data) {
        print $data->toHTML();
}
?><BR>
		写真をクリックすると大きいサイズの写真が表示されます。</P>
		<HR>
		<DIV class="container">
<?php
if(!is_null($photo)) {
  foreach($photo as $data) {
    print $data->toHTMLthumbnail();
  }
}
if(!is_null($video)) {
  foreach($video as $data) {
    print $data->toHTMLthumbnail();
  }
}if(!is_null($sound)) {
  foreach($sound as $data) {
    print $data->toHTMLthumbnail();
  }
}?>
		</DIV>
		<HR>
		<A href="index.php">戻る</A>
	</BODY>
</HTML>
