<?php 



	// Database Information
	$db_info = array(
		'host' => 'localhost',
		'user' => 'root',
		'password' => '',
		'database' => 'ccssb'
	);

	// Emails of Universities Accepted
	$available_unis = array(
		'@city.ac.uk'
	);
	


	// Initiating the Sessions
	session_start();

	// An extra Import functionality for easier including classes
	function import($inc) {
		include(INC.'classes/class.'.$inc.'.php');
	}

	// Defining the Roots for Backend and Frontend
	define('ROOT', dirname(__FILE__).'/');
	define('HOME', 'http://localhost/ccssb/');

	// The Assets Folder for Backend and Frontend
	define('ASB', ROOT.'assets/');
	define('ASF', HOME.'assets/');
	define('API_URL', HOME.'api/index.php');

	// The Assets Folder for Backend and Frontend
	define('INC', ASB.'includes/');
	define('PAGE', INC.'pages/');
	define('CSS', ASF.'css/');
	define('JS', ASF.'js/');
	define('IMG', ASF.'images/');

	// Making the Available Universities into a Global Variable
	define('AVAILABLE_UNIS', serialize($available_unis));


?>