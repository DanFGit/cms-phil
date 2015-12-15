<?php
  include_once('../common/base.php');
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="text/css" rel="stylesheet" href="../css/style.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="admin.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>Admin - Phil Wilkinson</title>
  </head>
  <body>
    <?php
    include_once('header.php');
    if(isset($_SESSION['loggedin'])) { include "nav.php"; }
    ?>

    <div id="content">
      <?php if(isset($_SESSION['loggedin'])) {
        //ADMIN IS LOGGED IN




      } else {
        // NO ADMIN LOGGED IN
        echo "<div class='adminnotice'><span class='notice'>You shouldn't be here. Please login.</span></div>";
      } //end login check ?>
    </div>
  </body>
</html>
