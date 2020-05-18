<?php
require_once(dirname(__FILE__).'/tool-settings.php');

session_start() or die('session start failed');

$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');

$csrftoken = htmlspecialchars(hash('sha256', session_id()), ENT_QUOTES);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
	$token = filter_input(INPUT_POST, 'csrftoken');

	if(!isset($hashes[$username])) {
		$hash = '$2y$10$abcdefghijklmnopqrstuv';    // dummy hash
	} else {
		$hash = $hashes[$username];
	}

	if($csrftoken === $token && password_verify($password, $hash)) {
		session_regenerate_id(true);
		$_SESSION['username'] = $username;
		header('Location: index.php?admin');
		exit;
	} else {
		http_response_code(403);    // login failed
	}
}

echo <<<HTMLX
<!DOCTYPE html>
<head>
<title>ログインページ</title>
</head>
<body>
<h1>ログインしてください</h1>
<form method="post" action="">
	ユーザ名: <input type="text" name="username" value="">
	パスワード: <input type="password" name="password" value="">
	<input type="hidden" name="csrftoken" value="$csrftoken">
	<input type="submit" value="ログイン">
</form>
</body>
</html>
HTMLX;
