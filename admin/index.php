<?php
  //Connect to database and start session
  include_once('../common/base.php');

  //Login form submitted
  if(isset($_POST['email']) && isset($_POST['password'])) {
    $checkLogin = "SELECT email, name FROM user WHERE email=:email AND password=:pass LIMIT 1";

    try {
      $loginCheckResult = $db->prepare($checkLogin);
      $loginCheckResult->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
      $loginCheckResult->bindParam(':pass', $_POST['password'], PDO::PARAM_STR);
      $loginCheckResult->execute();

      if($loginCheckResult->rowCount()==1) {
        $admin = $loginCheckResult->fetch(PDO::FETCH_ASSOC);

        $_SESSION['email'] = htmlentities($_POST['email'], ENT_QUOTES);
        $_SESSION['name'] = $admin['name'];
        $_SESSION['loggedin'] = 1;

        $notices .= "<div class='adminnotice'><span class='success'>Login Successful! Redirecting...</span></div>";

        if(isset($_GET['redirect'])) {
          echo "<meta http-equiv='refresh' content='0;URL=" . $_GET['redirect'] . ".php'>";
        } else {
          echo "<meta http-equiv='refresh' content='0'>";
        } ?>

      <?php } else {
        $notices .= "<div class='adminnotice'><span class='notice'>No such user/password.</span></div>";
      }
    } catch (PDOException $e) {
      echo 'Login failed: ' . $e->getMessage();
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="text/css" rel="stylesheet" href="../css/style.css"  media="screen,projection"/>
    <link type="text/css" rel="stylesheet" href="admin.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title><?php echo $me['forename'] . " " . $me['surname']; ?></title>
  </head>
  <body>
    <?php
    include_once('header.php');
    if(isset($_SESSION['loggedin'])) { include "nav.php"; }
    ?>

    <div id="adminhome">

      <?php if(isset($_SESSION['loggedin'])) {
        //Admin is logged in
        if(isset($notices)) echo $notices; ?>

        <h1>Hello, <?php echo $_SESSION['name']; ?></h1>

        <ul>
          <li><a href="upload.php">Upload a new image</a></li>
          <li><a href="new.php">Create a new project</a></li>
          <li><a href="homepage.php">Change your details</a></li>
          <li><a href="projects.php">View your projects</a></li>
        </ul>

      <?php } else {
        //Admin isn't logged in ?>

        <form id="login-form" action="index.php" method="POST">
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

      <?php } ?>

    </div><!--end content -->
    <div id="console">
      <?php print "<pre>"; print_r($GLOBALS); print "</pre>"; ?>
    </div>
    <!--Import jQuery-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  </body>
</html>
