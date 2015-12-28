<?php
  //Connect to database and start session
  include_once('../common/base.php');

  //Destroy session
	session_unset();
	session_destroy();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="text/css" rel="stylesheet" href="../css/style.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="admin.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
		<meta http-equiv="refresh" content="2;index.php">
    <title>Admin - <?php echo $me['forename'] . " " . $me['surname']; ?></title>
  </head>
  <body>
    <?php
    include_once('header.php');
    ?>

    <div id="content">

      <div class="adminnotice"><span class="success">You have been logged out. Redirecting...</span></div>

    </div><!--end content -->

  </body>
</html>
