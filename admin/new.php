<?php
  //Connect to database and start session
  include_once('../common/base.php');

  //All database queries


  function showForm($colour, $title = '', $summary = '', $content = '', $image = '') {?>
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
        <label for="title">Preview Image <emphasis>(371x195px recommended)</emphasis></label>
        <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
        <input name="previewpic" type="file" style="font-size: 15px; margin: 5px 0 0;"/>
            <br><br>

        <button style="border: 1px solid #<?php echo $colour; ?>;" type="submit" name="action" value="savePost">Save Post</button>
        <button style="border: 1px solid #<?php echo $colour; ?>;" type="submit" name="action" value="previewPost">Preview Post</button>
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
    <title>Admin - <?php echo $me['forename'] . " " . $me['surname']; ?></title>
  </head>
  <body>
    <?php
    include_once('header.php');
    if(isset($_SESSION['loggedin'])) { include "nav.php"; }
    ?>

    <div id="content">

      <?php if(isset($_SESSION['loggedin'])) {
        //Admin is logged in

        if(isset($_POST['action']) && $_POST['action'] == "previewPost") {

          if($_FILES["previewpic"]["error"] == 0){
            $target_dir = "../img/upload/";
            $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            $imageCheck = getimagesize($_FILES["headerpic"]["tmp_name"]);
            if (file_exists($target_file)) $fileExists = true;
            if($imageCheck == false) {
              //fileupload is not an image
              echo "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
            } elseif($fileExists) {
              //fileupload already exists
              echo "<div class='adminnotice'><span class='notice'>Sorry, " . $target_file . " already exists. Rename your file then reupload!</span></div>";
            } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
              //fileupload is not a supported image type
              echo "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
            } else {
              //fileupload is an image
              if (move_uploaded_file($_FILES["headerpic"]["tmp_name"], $target_file)) {
                echo "<div class='adminnotice'><span class='success'>Your new header image has been uploaded.</span></div>";
              } else {
                echo "<div class='adminnotice'><span class='notice'>Sorry, there was an error uploading your file.</span></div>";
              }
            }
          }

          ?>

          <div class="previewHeader"><h1>On the homepage:</h1></div>
          <div class="projectPreview">
            <div class="previews">
              <a href="#"><img src="<?php echo '../' . $_POST['image']; ?>" alt="<?php echo $_POST['title']; ?>"></a>
            </div>
            <div class="description">
              <a href="#"><h1><?php echo $_POST['title']; ?></h1></a>
                <?php echo nl2br($_POST['summary']); ?>
                <br><a class="more">Read More &raquo;</a>
            </div>
          </div>
          <div class="previewHeader"><h1>On the project page:</h1></div>
          <div class="projectPage preview">

                <h1><?php echo $_POST['title']; ?></h1>
                <?php echo nl2br($_POST['content']); ?>

          </div>

          <?php showForm($me['colour'], $_POST['title'], $_POST['summary'], $_POST['content'], $_POST['image']);
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
          showForm($me['colour']);
        }
      } else {
        // NO ADMIN LOGGED IN
        echo "You shouldn't be here.";
      } //end login check ?>
    </div>
  </body>
</html>
