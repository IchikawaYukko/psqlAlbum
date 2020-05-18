<?php
require_once('settings.php');
header("Content-Type: application/javascript; charset=utf-8");
require_once(dirname(__FILE__).'/palbum.php');
require_once(dirname(__FILE__).'/pgpx.php');

$db = new DBconn($db_param);
$db->conn();

$album = new Album($_GET['aid']);
$gpx = GPX::getGPXsInDateRange($album->getDatebegin(), $album->getDateend());

echo "var gpx = ". GPX::arrayToJSON($gpx);
