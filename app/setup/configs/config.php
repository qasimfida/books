<?php
	
	# |===============================================
	# | DEVELOPMENT ENVIRONMENT
	# | Auto move to production environment when not running on local network
	# |===============================================
	$devEnvironments = ['localhost', '127.0.0.1', '::1'];
	if( in_array($_SERVER['REMOTE_ADDR'], $devEnvironments) === TRUE && TRUE ){

		# MYSQL Database Configuration
		define('DB_HOSTNAME', 'localhost');
		define('DB_USERNAME', 'root');
		define('DB_PASSWORD', '');
		define('DB_NAME', 'books');
		define('DB_ERROR', true);
		define("ENVIRONMENT", "DEV");

	# |===============================================
	# | PRODUCTION CONFIGURATION
	# |===============================================
	}else{

		# MYSQL Database Configuration
		define('DB_HOSTNAME', 'localhost');
		define('DB_USERNAME', 'myxtaomy_qasim');
		define('DB_PASSWORD', 'Xuym%CQ0&#!x');
		define('DB_NAME', 'myxtaomy_AW-Dev3');
		define('DB_ERROR', false);
		define('ENVIRONMENT', "PROD");

	}
	
?>