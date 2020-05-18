<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<?php
require_once('settings.php');
require_once(dirname(__FILE__).'/palbum.php');
require_once(dirname(__FILE__).'/pphoto.php');
require_once(dirname(__FILE__).'/pvideo.php');
require_once(dirname(__FILE__).'/psound.php');
require_once(dirname(__FILE__).'/pgpx.php');
require_once(dirname(__FILE__).'/psns.php');

$photo; $video; $sound; $query_string; $sns; $result_count;
$db = new DBconn($db_param);

function init() {
	global $photo, $video, $sound, $db, $query_string, $sns, $result_count;
	global $psqlAlbum;

	$db->conn();
	$query_string = htmlspecialchars($_GET['query'], ENT_QUOTES|'ENT_HTML401');
	try {
		$photo = Photo::getObjectsBySearchQuery($query_string);
		$sns = new SNS(title(), $psqlAlbum['Description'], $photo[0]->getFileURL());
	} catch (Exception $e) {
		$sns = new SNS(title(), $psqlAlbum['Description'], NULL);
	}

	try {
		$video = Video::getObjectsBySearchQuery($query_string);
	} catch(Exception $e) {
	}

	try {
		$sound = Sound::getObjectsBySearchQuery($query_string);
	} catch(Exception $e) {
	}

	$result_count = count($photo) + count($video) + count($sound);
}

function title() {
	global $psqlAlbum, $query_string;

	return "検索結果:".$query_string." - ".$psqlAlbum['AlbumName'];
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
		<link rel="canonical" href="<?php print $psqlAlbum['DomainName'] . $_SERVER['REQUEST_URI']; ?>">
		<TITLE><?php print title(); ?></TITLE>
	</HEAD>
	<BODY>
		<H1><?php print "「{$query_string}」の検索結果({$result_count}件)"; ?></H1>
		<FORM method="GET" action="index.php" accept-charset="UTF-8">
			<INPUT type="text" name="query" maxlength="64" value="<?php print $query_string; ?>" placeholder="アルバム全体を検索">
			<INPUT type="submit" value="検索">
		</FORM>

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
}
if(!is_null($sound)) {
	foreach($sound as $data) {
		print $data->toHTMLthumbnail();
	}
}
?>
		</DIV>
		<HR>
		<A href="index.php">戻る</A>
	</BODY>
</HTML>
