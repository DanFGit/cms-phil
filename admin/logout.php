<?php
	
	session_start();
	
	unset($_SESSION['email']);
	unset($_SESSION['loggedin']);
	
?>

<meta http-equiv="refresh" content="0;index.php">