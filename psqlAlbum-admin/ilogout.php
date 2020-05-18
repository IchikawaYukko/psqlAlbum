<?php
session_start() or die('session start failed');

if(!isset($_SESSION['username'])) {
	die('you are not logged in yet');
}

$csrftoken = htmlspecialchars(hash('sha256', session_id()), ENT_QUOTES);
if($csrftoken !== $_GET['token']) {
	http_response_code(400);
	die('Invalid Token');
} else {
	// delete session and session cookie
	setcookie(session_name(), '', 1);
	session_destroy();

	echo 'Logged out.';
}
