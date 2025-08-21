<?php
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$lang = isset($_GET['lang']) ? $_GET['lang'] : null;
if ($lang !== 'en' && $lang !== 'my') {
	$lang = 'my';
}
$_SESSION['lang'] = $lang;

$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'http://localhost/healthy_meal_plan/';
header('Location: ' . $redirect);
exit;
?>


