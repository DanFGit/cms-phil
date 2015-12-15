<?php

  include_once('common/base.php');

  $sql = "SELECT * FROM projects";
  try {
    $stmt = $db->prepare($sql);
    $stmt->execute();
  } catch (PDOException $e) {
    echo 'Could not fetch project list: ' . $e->getMessage();
  }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link type="text/css" rel="stylesheet" href="css/style.css"  media="screen,projection"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
    <title>Phil Wilkinson</title>
  </head>
  <body>
    <?php include_once('common/header.php'); ?>

    <div id="content">

      <div id="about">
        <h1>About Me</h1>
        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam a luctus lorem. Fusce sed mauris ac turpis suscipit aliquet. Maecenas in eros massa. Proin posuere odio sit amet orci ultricies interdum. Aenean rutrum nunc sem. Ut at nunc malesuada, vestibulum purus ac, congue massa. Praesent feugiat vestibulum viverra.

        Sed convallis egestas turpis sed rutrum. Phasellus bibendum enim eu erat ornare aliquam. Nam sit amet eros mattis, vulputate velit id, malesuada tellus. Nulla quis augue venenatis urna hendrerit consequat. Cras nec suscipit dolor. Praesent malesuada sapien neque, et viverra ligula viverra non. Cras dignissim dui in semper scelerisque. Proin lorem urna, eleifend ac ultrices sit amet, congue iaculis mi. Fusce ac sem ipsum. Duis sem orci, euismod vehicula felis sed, tincidunt tincidunt felis. Sed mollis luctus tincidunt. Praesent a elit neque. Proin erat enim, elementum non ligula sollicitudin, scelerisque ultricies sem. Etiam faucibus ipsum in metus sagittis laoreet. Vestibulum lobortis pharetra congue.
      </div>

      <?php
      for($i = 0; $i < $stmt->rowCount(); $i++){

        $result = $stmt->fetch(PDO::FETCH_ASSOC); ?>
        <div class="project">
          <div class="previews">
            <a href="post.php?id=<?php echo $result['id']; ?>"><img src="<?php echo $result['image']; ?>" alt="<?php echo $result['title']; ?>"></a>
          </div>
          <div class="description">
            <a href="post.php?id=<?php echo $result['id']; ?>"><h1><?php echo $result['title']; ?></h1></a>
              <?php echo nl2br($result['summary']); ?>
              <br><a class="more" href="post.php?id=<?php echo $result['id']; ?>">Read More &raquo;</a>
          </div>
        </div>
      <?php } ?>

    </div>

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  </body>
</html>
