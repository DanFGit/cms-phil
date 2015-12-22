<?php
	session_start();

	//Get database connection details
	include_once('constants.inc.php');

	//Connect to the database
	try {
		$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PASS);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
		exit;
	}

?>
