<?php
	error_reporting(E_ALL);
	ini_set("display_errors", 1);
	
	session_start();
	
	include_once "constants.inc.php";
	
	try {
		$dsn = "mysql:host=".DB_HOST.";dbname=".DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PASS);
	} catch (PDOException $e) {
		echo 'Connection failed: ' . $e->getMessage();
		exit;
	}
	
?>