<?php
error_reporting(0);  
ini_set('display_errors', 0); 
	/***
	 * NAP - Vanilla PHP REST-API Boilerplate
	 * https://github.com/frozeeen/Nap-PHP
	 ***/

	/** API Boilerplate Configurations */
	define('APPROOT', dirname( __FILE__ ) . '/');
	
	/*** 
	 * Once the API is loaded, this bootstrap will start the sequence
	 * by calling the core constructor which will load the required controller
	 * based on the client request
	 ***/
	session_start();
	require_once 'setup/configs/config.php';
	require_once 'setup/helpers/Utilities.php';

	/** Error Reporting */
	if( ENVIRONMENT != "DEV" ){
		error_reporting(0);
		set_error_handler(function(){
			http_response_code(500);
			echo json_encode([
				"status" => false,
				"message" => "Something went wrong under the hood"
			]); exit;
		});
	}

	/** Load primary cores */
	$DRIVERS = [

		# Required Drivers
		'Api',
		'Core',
		'Database',
		'Model',
		'Routing',

		# Optional Drivers
		'Validate',
		'File'
	];
	require_once 'private/cores/Api.php';
	require_once 'private/cores/Core.php';
	require_once 'private/cores/Database.php';
	require_once 'private/cores/Model.php';
	require_once 'private/cores/Routing.php';
	require_once 'private/cores/Validate.php';
	require_once 'private/cores/File.php';

	foreach ($DRIVERS as $value){
		require_once 'private/Cores/' . $value . '.php';
	}
?>