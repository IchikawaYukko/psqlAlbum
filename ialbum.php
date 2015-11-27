<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
  require_once('settings.php');
  require_once(dirname(__FILE__).'/palbum.php');
  require_once(dirname(__FILE__).'/pphoto.php');
  require_once(dirname(__FILE__).'/pvideo.php');
  require_once(dirname(__FILE__).'/pgpx.php');

  $album; $photo; $video; $gpx;
  $db = new DBconn($db_param);

  function init() {
    global $photo, $video, $album, $gpx, $db;

	$db->conn();
	$album = new Album($_GET['id']);
	//$album = new Album(2);
	$photo = Photo::getObjectsInDateRange($album->getDatebegin(), $album->getDateend());
	$video = Video::getObjectsInDateRange($album->getDatebegin(), $album->getDateend());
	$gpx = GPX::getGPXsInDateRange($album->getDatebegin(), $album->getDateend());
  }

  function title() {
    global $album, $psqlAlbum;

    return $album->getTitle()." - ".$psqlAlbum['AlbumName'];
  }

  init();
?>
<HTML LANG="<?php print $psqlAlbum['SiteLang']; ?>">
	<HEAD>
		<?php require_once(dirname(__FILE__).'/metatags.php'); //共通の<meta>をファイルからインポート ?>
		<!-- facebook ogp -->
			<meta property="og:title" content="<?php print $psqlAlbum['AlbumName']; ?>">
			<meta property="og:type" content="article">
			<meta property="og:description" content="<?php print $psqlAlbum['Description']; ?>">
			<meta property="og:url" content="<?php print $psqlAlbum['AlbumRoot']; ?>">
			<meta property="og:image" content="http://ichikawayukko.mydns.jp/Nanjing/thumbs/20120508/SAM_1816.JPG">
			<meta property="og:site_name" content="<?php print $psqlAlbum['AlbumName']; ?>">
			<meta property="og:email" content="">
		<!-- Twitter Card -->
			<meta name="twitter:card" content="photo" />
			<meta name="twitter:site" content="@IchikawaYukko" />
			<meta name="twitter:title" content="<?php print($album->getTitle()); ?>" />
			<meta name="twitter:image" content="<?php print $psqlAlbum['AlbumRoot']; ?>" />
			<meta name="twitter:url" content="<?php print $psqlAlbum['AlbumRoot'].'index.php?aid='.$_GET['id']; ?>" />
		<LINK rel="stylesheet" type="text/css" href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css">
		<link rel="canonical" href="<?php print $psqlAlbum['AlbumRoot'].'index.php?aid='.$_GET['id']; ?>">
		<SCRIPT language="JavaScript" src="<?php print $psqlAlbum['LibDir']; ?>OpenLayers.js"></SCRIPT>
		<SCRIPT language="JavaScript" src="http://www.openstreetmap.org/openlayers/OpenStreetMap.js"></SCRIPT>
		<SCRIPT language="JavaScript" src="<?php print $psqlAlbum['AlbumLibDir']; ?>map.js"></SCRIPT>
		<SCRIPT language="JavaScript"><!--
		  var gpx = <?php print GPX::arrayToJSON($gpx); ?>
		// --></SCRIPT>
		<TITLE><?php print ($album->getTitle()." - ".$psqlAlbum['AlbumName']); ?></TITLE>
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
		<DIV class="container clearfix">
<?php
foreach($photo as $data) {
	print $data->toHTMLthumbnail();
}
foreach($video as $data) {
	print $data->toHTMLthumbnail();
}?>
		</DIV>
		<HR>
		<A href="index.php">戻る</A>
	</BODY>
</HTML>
