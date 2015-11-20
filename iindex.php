<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"  "http://www.w3.org/TR/html4/strict.dtd">
<?php
require_once('settings.php');
require_once(dirname(__FILE__).'/palbum.php');

$album;
$db = new DBconn($db_param);
$db->conn();

function init() {
	global $album;

	$album = Album::getAllAlbum();
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
		<META name="GENERATOR" content="pSQLAlbum 1.0 by IchikawaYuriko">
		<!-- facebook ogp -->
			<meta property="og:title" content="<?php print $psqlAlbum['AlbumName']; ?>">
			<meta property="og:type" content="website">
			<meta property="og:description" content="<?php print $psqlAlbum['Description']; ?>">
			<meta property="og:url" content="http://ichikawayukko.mydns.jp/Nanjing/">
			<meta property="og:image" content="http://ichikawayukko.mydns.jp/Nanjing/thumbs/20120508/SAM_1816.JPG">
			<meta property="og:site_name" content="<?php print $psqlAlbum['AlbumName']; ?>">
			<meta property="og:email" content="">
		<!-- Twitter card -->
			<meta name="twitter:card" content="gallery">
			<meta name="twitter:site" content="@IchikawaYukko">
			<meta name="twitter:creator" content="@IchikawaYukko">
			<meta name="twitter:domain" content="ichikawayukko.mydns.jp">
			<meta name="twitter:title" content="<?php print $psqlAlbum['AlbumName']; ?>">
			<meta name="twitter:description" content="<?php print $psqlAlbum['Description']; ?>">
			<meta name="twitter:url" content="http://ichikawayukko.mydns.jp/Nanjing/">
			<META name="twitter:image0" content="http://ichikawayukko.mydns.jp/Nanjing/thumbs/20120510-0511/SAM_1964.JPG">
			<META name="twitter:image1" content="http://ichikawayukko.mydns.jp/Nanjing/thumbs/20120509/SAM_1848.JPG">
			<META name="twitter:image2" content="http://ichikawayukko.mydns.jp/Nanjing/thumbs/20120509/SAM_1913.JPG">
			<META name="twitter:image3" content="http://ichikawayukko.mydns.jp/Nanjing/thumbs/20120508/SAM_1816.JPG">
		<TITLE><?php print $psqlAlbum['AlbumName']; ?></TITLE>
		<LINK rel="stylesheet" type="text/css" href="<?php print $psqlAlbum['AlbumLibDir']; ?>album.css">
		<link rel="canonical" href="<?php print $psqlAlbum['AlbumRoot']; ?>">
		<Style type="text/css">
		<!--
.fb_iframe_widget > span
{
    vertical-align: baseline !important;
}
		// -->
		</Style>
	</HEAD>
	<BODY>

<!-- Facebook いいね！ -->
<div id="fb-root"></div>
<script type="text/javascript">    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/ja_JP/sdk.js#xfbml=1&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    } (document, 'script', 'facebook-jssdk'));
</script>

	<H1><?php print $psqlAlbum['AlbumName']; ?></H1>
<?php require_once('description.php'); ?>

	<div class="fb-like" data-href="http://ichikawayukko.mydns.jp/Nanjing/" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
	<a href="http://b.hatena.ne.jp/entry/http://ichikawayukko.mydns.jp/Nanjing/" class="hatena-bookmark-button" data-hatena-bookmark-title="南京理工大学留学記・2011～2012" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="ja" title="このエントリーをはてなブックマークに追加"><img src="https://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="このエントリーをはてなブックマークに追加" width="20" height="20" style="border: none;"></a><script type="text/javascript" src="https://b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>
	<a href="https://twitter.com/share" class="twitter-share-button" data-via="IchikawaYukko">Tweet</a>
	<script type="text/javascript">                    !function (d, s, id) { var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location) ? 'http' : 'https'; if (!d.getElementById(id)) { js = d.createElement(s); js.id = id; js.src = p + '://platform.twitter.com/widgets.js'; fjs.parentNode.insertBefore(js, fjs); } } (document, 'script', 'twitter-wjs');</script>

	<HR>
	<FORM method="GET" action="index.php" accept-charset="UTF-8">
		<INPUT type="text" name="query" maxlength="64" placeholder="アルバム全体を検索">
		<INPUT type="submit" value="検索">
	</FORM>
	<HR>
<?php
foreach($album as $data) {
  print $data->toHTML();
}
?>
		<A href="album.php?datebegin=20120101&dateend=20120627&photo=no">ビデオアルバム</A>
	</BODY>
</HTML>
