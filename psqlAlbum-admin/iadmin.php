<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN"  "http://www.w3.org/TR/html4/strict.dtd">
<?php

require_once(__DIR__.'/../pdbconn.php');
require_once('settings.php');
require_once(dirname(__FILE__).'/tool-settings.php');

$html;

function init() {
    global $psqlAlbum, $album, $db_param, $container, $html;
	
	session_start() or die('session start failed');

	if(!isset($_SESSION['username'])) {
		header('Location: index.php?login');
		exit;
	}

	$swift_objects = [];
  
	exec("source ".__DIR__."/../OpenStackAuth.sh;swift list $container", $output);
  
    $db = new DBconn($db_param);
	$db->conn();
	
	$count = [
		'photo' => 0,
		'thumbnail' => 0,
		'gpx' => 0,
	];

	foreach ($output as $line) {
		$object = [
			'swift_filename' => $line,
			'swift_object_type' => null,
			'db_id' => null,
			'db_filename' => null,
		];

		if (stripos($object['swift_filename'], '.jpg') !== false) {
			$object['swift_object_type'] = 'Photo';

			$search_string = substr($object['swift_filename'], strpos($object['swift_filename'], '/') + strlen('/'));

			$db->query("SELECT id, filename FROM photo WHERE filename LIKE $1", ["%$search_string"]);
			while($db->hasMoreRows()) {
				$result = $db->nextRow();
				$object['db_id'] = $result['id'];
				$object['db_filename'] = $result['filename'];
			}

			$count['photo']++;
		}

		if (stripos($object['swift_filename'], '.gpx') !== false) {
			$object['swift_object_type'] = 'GPX';

			$search_string = substr($object['swift_filename'], strpos($object['swift_filename'], 'GPX/') + strlen('GPX/'));

			$db->query("SELECT id, filename FROM gpx WHERE filename LIKE $1", ["%$search_string"]);
			while($db->hasMoreRows()) {
				$result = $db->nextRow();
				$object['db_id'] = $result['id'];
				$object['db_filename'] = $result['filename'];
			}

			$count['gpx']++;
		}

		if (strpos($object['swift_filename'], 'thumbs/') !== false) {
			$object['swift_object_type'] = 'Thumbnail';

			$search_string = substr($object['swift_filename'], strpos($object['swift_filename'], '/', strpos($object['swift_filename'], 'thumbs/') + strlen('thumbs/')) + strlen('/'));

			$db->query("SELECT id, filename FROM photo WHERE filename LIKE $1", ["%$search_string"]);
			while($db->hasMoreRows()) {
				$result = $db->nextRow();
				$object['db_id'] = $result['id'];
				$object['db_filename'] = $result['filename'];
			}
			$count['thumbnail']++;
		}

		array_push($swift_objects, $object);
	}

	$html = "{$count['photo']} Photos, {$count['thumbnail']} Thunbnails,{$count['gpx']} GPXs";
	$html .= '<table>';
	$html .= '<tr><td>Obj Storage filename</td><td>Data Type</td><td>DB ID</td><td>DB filename</td></tr>';

	foreach($swift_objects as $object) {
		foreach($object as $key => $value) {
			if ($value == null) {
				$html .= "<tr><td>{$object['swift_filename']}</td><td>{$object['swift_object_type']}</td><td>{$object['db_id']}</td><td>{$object['db_filename']}</td></tr>";
			}
		}
	}
	$html .= '</table>';

	$csrftoken = htmlspecialchars(hash('sha256', session_id()), ENT_QUOTES);
}

init();
?>
<HTML LANG="<?php print $psqlAlbum['SiteLang']; ?>">
	<HEAD>
<?php
require_once(dirname(__FILE__).'/../metatags.php'); //共通の<meta>をファイルからインポート

$sitename = $psqlAlbum['AlbumName'];
$libdir = $psqlAlbum['AlbumLibDir'];
$albumroot = $psqlAlbum['AlbumRoot'];

echo <<<HEREDOC
		<TITLE>サイト設定$sitename</TITLE>
		<LINK rel="stylesheet" type="text/css" href="{$libdir}album.css">
		<link rel="canonical" href="$albumroot">
		<style>
		td {
			border: 1px black solid;
		}
		</style>
	</HEAD>
	<BODY>
HEREDOC;
?>
	<H1><?php print $psqlAlbum['AlbumName']; ?></H1>
	<HR>
	<?php
		echo "{$_SESSION['username']}さんでログインしています。<a href=\"index.php?logout&token=$csrftoken\">ログアウト</a><br>";
		echo $html;
	?>
	</BODY>
</HTML>
