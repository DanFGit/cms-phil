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

        } elseif(isset($_POST['email']) && isset($_POST['password'])) {
        //LOGIN SUBMITTED
        $sql = "SELECT email FROM user WHERE email=:email AND password=:pass LIMIT 1";

        try {
          $stmt = $db->prepare($sql);
          $stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
          $stmt->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
          $stmt->execute();

          if($stmt->rowCount()==1) {
            $_SESSION['email'] = htmlentities($_POST['email'], ENT_QUOTES);
            $_SESSION['loggedin'] = 1;
            ?><meta http-equiv="refresh" content="0"><?php
          } else {
            echo "Incorrect email/password.";
          }
        } catch (PDOException $e) {
  		    echo 'Login failed: ' . $e->getMessage();
        }


      } else {
        // NO ADMIN LOGGED IN ?>

      <form id="login-form" method="POST">
        <div>
          <label for="email">Email</label>
          <input type="text" name="email" id="email" />
          <br><br>
          <label for="password">Password</label>
          <input type="password" name="password" id="password" />
          <br><br>
          <input type="submit" name="login-submit" id="login-submit" value="Log In" class="button" />
        </div>
      </form>

    </div><!--end content -->

    <?php } //end login check ?>
  </body>
</html>
