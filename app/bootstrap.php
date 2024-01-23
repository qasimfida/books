<?php
error_reporting(0);
ini_set('display_errors', 0);


/** API Boilerplate Configurations */
define('APPROOT', dirname(__FILE__) . '/');


session_start();
require_once 'setup/configs/config.php';
require_once 'setup/helpers/Utilities.php';

/** Error Reporting */
if (ENVIRONMENT != "PROD") {
	error_reporting(0);
	set_error_handler(function () {
		http_response_code(500);
		echo json_encode([
			"status" => false,
			"message" => "Something went wrong under the hood"
		]);
		exit;
	});
}

require_once 'private/cores/Api.php';
require_once 'private/cores/Core.php';
require_once 'private/cores/Database.php';
require_once 'private/cores/Model.php';
require_once 'private/cores/Routing.php';
require_once 'private/cores/Validate.php';
require_once 'private/cores/File.php';
