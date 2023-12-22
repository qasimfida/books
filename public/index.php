<?php
header('Access-Control-Allow-Methods', '*');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials', 'true');
header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
header("Content-Type: application/json; charset=UTF-8");

require_once '../app/bootstrap.php';

$requestUri = $_SERVER['REQUEST_URI'];
$filePath = __DIR__ . $requestUri;

if (is_file($filePath)) {
	$fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
	$allowedExtensions = ['png', 'jpg', 'jpeg', 'gif'];

	if (in_array($fileExtension, $allowedExtensions)) {
		header("Content-Type: image/{$fileExtension}");
		readfile($filePath);
		exit();
	}
}
$init = new Core();
