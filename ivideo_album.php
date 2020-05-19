<?php
require_once('settings.php');
require_once(dirname(__FILE__).'/palbum.php');
require_once(dirname(__FILE__).'/pphoto.php');
require_once(dirname(__FILE__).'/pvideo.php');
require_once(dirname(__FILE__).'/psound.php');
require_once(dirname(__FILE__).'/pgpx.php');
require_once(dirname(__FILE__).'/psns.php');

$album; $photo; $video; $sound; $gpx; $sns; $title;
$db = new DBconn($db_param);

function init() {
	global $psqlAlbum, $photo, $video, $sound, $album, $gpx, $db, $sns, $title;

	$db->conn();
	try {
		$video = Video::getObjectsBySearchQuery("");
	} catch(Throwable $th) {
		header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
		die($th->getMessage());
	}

	$title = "ビデオアルバム";
	$sns = new SNS($title, $psqlAlbum['Description'], NULL);
}

function title() {
	global $psqlAlbum, $title;

	return "$title - ".$psqlAlbum['AlbumName'];
}

init();
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<HTML LANG="<?php print $psqlAlbum['SiteLang']; ?>">
	<HEAD>
<?php
require_once(dirname(__FILE__).'/metatags.php'); //共通の<meta>をファイルからインポート

print $sns->toFacebookOGP('article');
print $sns->toTwitterCards('photo');
?>
		<LINK rel="stylesheet" type="text/css" href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css">
		<link rel="canonical" href="<?php print $psqlAlbum['AlbumRoot'].'index.php?aid='.$_GET['aid']; ?>">
		<SCRIPT language="JavaScript" src="<?php print $psqlAlbum['LibDir']; ?>OpenLayers.js"></SCRIPT>
		<SCRIPT language="JavaScript" src="https://www.openstreetmap.org/openlayers/OpenStreetMap.js"></SCRIPT>
		<SCRIPT language="JavaScript" src="index.php?aid=<?php echo $_GET['aid'] ?>&getgpx=true"></SCRIPT>
		<SCRIPT language="JavaScript" src="<?php print $psqlAlbum['AlbumLibDir']; ?>map.js"></SCRIPT>
		<TITLE><?php print (title()); ?></TITLE>
	</HEAD>
	<BODY>
		<H1><?php print $title; ?></H1>
		<P><STRONG>概要：</STRONG><?php print $title; ?><BR><BR></P>
<BR>
		写真をクリックすると大きいサイズの写真が表示されます。</P>
		<HR>
		<DIV class="container">
<?php
if(!is_null($video)) {
	foreach($video as $data) {
		print $data->toHTMLthumbnail();
	}
}?>
		</DIV>
		<HR>
		<A href="index.php">戻る</A>
	</BODY>
</HTML>
