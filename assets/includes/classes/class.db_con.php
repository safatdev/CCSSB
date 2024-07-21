<?php

class DBCon {

	public $con;

	// Making a database connection
	public function __construct($d) {
		if (!$this->con = new mysqli($d['host'], $d['user'], $d['password'], $d['database'])) {
			echo '<h1>Database connection error.</h1>';
			exit();
		}
	}

	// Closing the connection
	public function close() {
		$this->con->close();
	}

}

?>