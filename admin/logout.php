<?php

	session_start();

	unset($_SESSION['email']);
	unset($_SESSION['loggedin']);

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="text/css" rel="stylesheet" href="../css/style.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="admin.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="refresh" content="0;../index.php">
    <title>Admin - Phil Wilkinson</title>
  </head>
  <body>
    <?php
    include_once('header.php');
    ?>

    <div id="content">

      <div class="adminnotice">You have been logged out. Redirecting...</div>

    </div><!--end content -->

  </body>
</html>
