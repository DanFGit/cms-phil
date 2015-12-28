<?php
  //Connect to database and start session
  include_once('common/base.php');

  //Do all database queries here
  if(isset($_GET['id'])){
    $sql = "SELECT * FROM projects WHERE id=:id";
    try {
      $stmt = $db->prepare($sql);
      $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
      $stmt->execute();
      $result = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      echo 'No such post: ID' . $_GET['id'];
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="text/css" rel="stylesheet" href="css/style.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title><?php echo $result['title']; ?> // <?php echo $me['forename'] . " " . $me['surname']; ?></title>
  </head>
  <body>
    <?php include_once('common/header.php'); ?>

    <div id="content" class="projectPage">

          <h1><?php echo $result['title']; ?></h1>
          <?php echo nl2br($result['content']); ?>

    </div>

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  </body>
</html>
