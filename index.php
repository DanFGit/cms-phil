<?php
  //Connect to database and start session
  include_once('common/base.php');

  //Get the project list
  $getProjects = "SELECT * FROM projects";
  try {
    $projectList = $db->prepare($getProjects);
    $projectList->execute();
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
    <title><?php echo $me['forename'] . " " . $me['surname']; ?></title>
  </head>
  <body>
    <?php include_once('common/header.php'); ?>

    <div id="content">

      <div id="about">
        <h1>About Me</h1>
        <?php echo $me['about']; ?>
      </div>

      <?php
      for($i = 0; $i < $projectList->rowCount(); $i++){

        $project = $projectList->fetch(PDO::FETCH_ASSOC);
        if($project['visible']) { ?>
          <div class="project">
            <div class="previews">
              <a href="post.php?id=<?php echo $project['id']; ?>"><img src="<?php echo $project['image']; ?>" alt="<?php echo $project['title']; ?>"></a>
            </div>
            <div class="description">
              <a href="post.php?id=<?php echo $project['id']; ?>"><h1><?php echo $project['title']; ?></h1></a>
                <?php echo nl2br($project['summary']); ?>
                <br><a style="color: #<?php echo $me['colour']; ?>;" class="more" href="post.php?id=<?php echo $project['id']; ?>">Read More &raquo;</a>
            </div>
          </div>
          <?php }
        } ?>

    </div>

    <!--Import jQuery before materialize.js-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  </body>
</html>
