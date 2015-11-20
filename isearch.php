<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
  require_once('settings.php');
  require_once(dirname(__FILE__).'/palbum.php');
  require_once(dirname(__FILE__).'/pphoto.php');
  require_once(dirname(__FILE__).'/pvideo.php');
  require_once(dirname(__FILE__).'/pgpx.php');

  $photo; $video; $query_string;
  $db = new DBconn($db_param);

  function init() {
    global $photo, $video, $db, $query_string;

	$db->conn();
	$query_string = htmlspecialchars($_GET['query'], ENT_QUOTES|'ENT_HTML401');
	$photo = Photo::getPhotosBySearchQuery($query_string);
	//$video = Video::getVideosInDateRange($album->getDatebegin(), $album->getDateend());
  }

  function title() {
    global $psqlAlbum, $query_string;

    return "検索結果:".$query_string." - ".$psqlAlbum['AlbumName'];
  }

  init();
?>
<HTML LANG="<?php print $psqlAlbum['SiteLang']; ?>">
	<HEAD>
		<META HTTP-EQUIV="Content-type" CONTENT="text/html; charset=UTF-8">
		<!-- < *｀∀´> このページのソースをご覧になるとはあなたも物好きですねぇ -->
		<!-- HTML生ソースの世界へようこそ -->
		<meta http-equiv="Content-Language" content="<?php print $psqlAlbum['SiteLang']; ?>">
		<META http-equiv="Content-Style-Type" content="text/css">
		<META name="robots" content="index,follow">
		<meta name="Author" content="<?php print $psqlAlbum['AlbumAuthor']; ?>">
		<meta name="description" content="<?php print $psqlAlbum['Description']; ?>">
		<meta name="viewport" content="initial-scale=1.0">
		<META name="GENERATOR" content="pSQLAlbum 1.0 by IchikawaYukko">
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
			<meta name="twitter:title" content="<?php //print($album->getTitle()); ?>" />
			<meta name="twitter:image" content="<?php print $psqlAlbum['AlbumRoot']; ?>" />
			<meta name="twitter:url" content="<?php //print $psqlAlbum['AlbumRoot'].'index.php?aid='.$_GET['id']; ?>" />
		<LINK rel="stylesheet" type="text/css" href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css">
		<link rel="canonical" href="<?php print $psqlAlbum['DomainName'] . $_SERVER['REQUEST_URI']; ?>">
		<TITLE><?php print title(); ?></TITLE>
	</HEAD>
	<BODY>
		<H1><?php print "「".$query_string."」の検索結果(".count($photo)."件)"; ?></H1>
		<FORM method="GET" action="index.php" accept-charset="UTF-8">
			<INPUT type="text" name="query" maxlength="64" value="<?php print $query_string; ?>" placeholder="アルバム全体を検索">
			<INPUT type="submit" value="検索">
		</FORM>

		写真をクリックすると大きいサイズの写真が表示されます。</P>
		<HR>
		<DIV class="container clearfix">
<?php
foreach($photo as $data) {
	print $data->toHTMLthumbnail();
}
//foreach($video as $data) {
//	print $data->toHTMLthumbnail();
//}?>
		</DIV>
		<HR>
		<A href="index.php">戻る</A>
	</BODY>
</HTML>
