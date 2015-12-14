<?php

  include_once('../common/base.php');

  function showForm($title = '', $summary = '', $content = '', $image = '') {?>
    <form id="updateProject" method="POST">
      <div>
        <label for="title">Title</label>
        <input type="text" name="title" id="title" value="<?php echo $title; ?>" required />
          <br><br>
        <label for="summary">Summary</label>
        <textarea rows="5" name="summary" id="summary" required><?php echo $summary; ?></textarea>
          <br><br>
        <label for="content">Content</label>
        <textarea rows="10" name="content" id="formcontent" required><?php echo $content; ?></textarea>
          <br><br>
        <label for="image">Image URL</label>
        <input type="text" name="image" id="image" value="<?php echo $image; ?>" required />
          <br><br>

        <button type="submit" name="action" value="savePost">Save Post</button>
        <button type="submit" name="action" value="previewPost">Preview Post</button>
      </div>
    </form>
  <?php }

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
    <header style="height:78px;">
      <div id="header_name">
        <span id="header_fname"><a href="index.php">admin</a></span>
      </div>
    </header>

    <?php if(isset($_SESSION['loggedin'])) { include "nav.php"; } ?>

    <div id="content">

      <?php if(isset($_SESSION['loggedin'])) {
        //ADMIN IS LOGGED IN

        if(isset($_POST['action']) && $_POST['action'] == "previewPost") { ?>

          <div class="previewHeader"><h1>On the homepage:</h1></div>
          <div class="projectPreview">
            <div class="previews">
              <a href="#"><img src="<?php echo '../' . $_POST['image']; ?>" alt="<?php echo $_POST['title']; ?>"></a>
            </div>
            <div class="description">
              <a href="#"><h1><?php echo $_POST['title']; ?></h1></a>
                <?php echo nl2br($_POST['summary']); ?>
            </div>
          </div>
          <div class="previewHeader"><h1>On the project page:</h1></div>

          <?php showForm($_POST['title'], $_POST['summary'], $_POST['content'], $_POST['image']);
        }
        elseif(isset($_POST['action']) && $_POST['action'] == "savePost") {

          $sql = "INSERT INTO projects (title, summary, image, content) VALUES
                  (:title, :summary, :image, :content)";

          try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
            $stmt->bindParam(':summary', $_POST['summary'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $_POST['image'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
            $stmt->execute();
          } catch (PDOException $e) {
    		    echo 'Insert failed: ' . $e->getMessage();
          }

          if($stmt->rowCount()==1) {
            echo "Success.";
            ?><meta http-equiv="refresh" content="0;projects.php"><?php
          } else {
            echo "Insert Failed.";
          }
        } else {
          showForm();


        }

      } else {
        // NO ADMIN LOGGED IN
        echo "You shouldn't be here.";

      } //end login check ?>
    </div>
  </body>
</html>
