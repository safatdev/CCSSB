<?php

/*
(c) 2020 Safat Sikder

This is Vanilla PHP, no frameworks were used when
creating this Project.
*/

// The Config File
require_once('config.php');


// Importing
import('db_con');
import('controller');


// The Pages
$pages = array(
	
	// Global Pages
	'Home',
	'Login',
	'Register',
	'News',
	'About',
	'Contact',

	// Dashboard Pages
	'Timeline',
	'Logout',
	'Verify',
	'Profile',
	'Post',

	// Testing Page
	// 'Test'

);


// creating the instances
$db = new DBCon($db_info);
$page = new Controller($db->con, $pages);


// Some Cleanup
$db->close();

?>