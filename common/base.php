<?php
	session_start();

	//Get database connection details
	include_once('constants.inc.php');

	//Store notices to be printed at top of page
	$notices = null;

	//Connect to the database
	try {
		$dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
		$db = new PDO($dsn, DB_USER, DB_PASS);
	} catch (PDOException $e) {
		$notices .= "<div class='adminnotice'><span class='notice'>Unable to connect to database: " .  $e->getMessage() . "</span></div>";
		exit;
	}

	//Pull the portfolio info
	$getSettings = "SELECT * FROM settings";
	try {
	  $settingsResult = $db->prepare($getSettings);
	  $settingsResult->execute();
	} catch (PDOException $e) {
	  $notices .= "<div class='adminnotice'><span class='notice'>Could not retrieve info from database: " . $e->getMessage() . "</span></div>";
	}

	//Store the portfolio info in $me
	if($settingsResult->rowCount()==1) {
	  $me = $settingsResult->fetch(PDO::FETCH_ASSOC);
	} else {
	  $notices .= "<div class='adminnotice'><span class='notice'>Could not retrieve info from database.</span></div>";
	}

?>
