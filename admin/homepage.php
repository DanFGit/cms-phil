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

        if(isset($_POST['action']) && $_POST['action'] == "saveChanges"){
          $sql = "UPDATE settings SET
                  forename=:forename,
                  surname=:surname,
                  skills=:skills,
                  about=:about,
                  colour=:colour
                  WHERE id=:id";

          $updateSkills = str_replace(array("\r\n", "\n\r", "\n", "\r"), "/", $_POST['skills']);
          $updateAbout = str_replace(array("\r\n", "\n\r", "\n", "\r"), "<br>", $_POST['about']);
          $updateColour = strtoupper($_POST['colour']);
          $id = 1;

          try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':forename', $_POST['forename'], PDO::PARAM_STR);
            $stmt->bindParam(':surname', $_POST['surname'], PDO::PARAM_STR);
            $stmt->bindParam(':skills', $updateSkills, PDO::PARAM_STR);
            $stmt->bindParam(':about', $updateAbout, PDO::PARAM_STR);
            $stmt->bindParam(':colour', $updateColour, PDO::PARAM_STR);
            $stmt->execute();
          } catch (PDOException $e) {
            echo 'Update failed: ' . $e->getMessage();
          }

          if($stmt->rowCount()==1) {
            echo "<div class='adminnotice'><span class='success'>Your information has been successfully updated.</span></div>";

            try {
              $settings_stmt = $db->prepare($settings_sql);
              $settings_stmt->execute();
            } catch (PDOException $e) {
              echo "Database Error: " . $e->getMessage();
            } if($settings_stmt->rowCount()==1) {
              $me = $settings_stmt->fetch(PDO::FETCH_ASSOC);
            }
          } else {
            echo "<div class='adminnotice'><span class='notice'>Settings Update Failed.</span></div>";
          }
        } elseif(isset($_POST['action']) && $_POST['action'] == "previewChanges"){
          echo "<div class='adminnotice'><span class='notice'>You have unsaved changes! Click Save Changes to apply them.</span></div>";
        }  elseif(isset($_POST['action']) && $_POST['action'] == "previewPicture"){
          echo "<div class='adminnotice'><span class='notice'>Happy with your changes?"; ?>

            <form id="saveChanges" method="POST">
              <div>
                <input type="hidden" name="profileChange" value="<?php echo $_FILES["profilepic"]["error"]; ?>" />
                <input type="hidden" name="headerChange" value="<?php echo $_FILES["headerpic"]["error"]; ?>" />
                <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="savePreview">Click here to Save Changes</button>
              </div>
            </form>

          <?php echo "</span></div>";
        } elseif(isset($_POST['action']) && $_POST['action'] == "savePreview"){
          if($_POST['profileChange'] == 0) {
            if(rename("../img/upload/preview.jpg", "../img/upload/me.jpg")){
              echo "<div class='adminnotice'><span class='success'>Profile Picture Updated Successfully</span></div>";
            } else {
              echo "<div class='adminnotice'><span class='notice'>Profile Picture Update Failed</span></div>";
            }
          }
          if($_POST['headerChange'] == 0) {
            if(rename("../img/upload/preview_header.png", "../img/upload/bg_header.png")){
              echo "<div class='adminnotice'><span class='success'>Header Updated Successfully</span></div>";
            } else {
              echo "<div class='adminnotice'><span class='notice'>Header Update Failed</span></div>";
            }
          }
        } elseif(isset($_POST['action']) && $_POST['action'] == "savePicture") {
          if($_FILES["profilepic"]["error"] == 0){
            $target_dir = "../img/upload/";
            $target_file = $target_dir . "me.jpg";

            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            $imageCheck = getimagesize($_FILES["profilepic"]["tmp_name"]);
            if($imageCheck == false) {
              //fileupload is not an image
              echo "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
            } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
              //fileupload is not a supported image type
              echo "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
            } else {
              //fileupload is an image
              if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
                echo "<div class='adminnotice'><span class='success'>Your new profile picture has been uploaded.</span></div>";
              } else {
                echo "<div class='adminnotice'><span class='notice'>Sorry, there was an error uploading your file.</span></div>";
              }
            }
          }
          if($_FILES["headerpic"]["error"] == 0){
            $target_dir = "../img/upload/";
            $target_file = $target_dir . "bg_header.png";

            $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

            $imageCheck = getimagesize($_FILES["headerpic"]["tmp_name"]);
            if($imageCheck == false) {
              //fileupload is not an image
              echo "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
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
        } ?>

        <form id="updateBio" method="POST">
          <div>
            <label for="forename">First Name</label>
            <input type="text" name="forename" id="forename" value="<?php echo $me['forename']; ?>" required />
              <br><br>
            <label for="surname">Last Name</label>
            <input type="text" name="surname" id="surname" value="<?php echo $me['surname']; ?>" required />
              <br><br>
            <label for="skills">Skills (put each on a new line)</label>
            <textarea rows="7" name="skills" id="skills" required><?php
              $skills = explode('/', $me['skills']);

              for($i = 0, $size = count($skills); $i < $size; $i++){
                echo $skills[$i];
                if($i != $size - 1) echo "\n";
              }
            ?></textarea>
              <br><br>
            <label for="about">About You</label>
            <textarea rows="10" name="about" id="about" required><?php echo str_replace("<br>", "\n", $me['about']); ?></textarea>
              <br><br>
            <label for="colour">Colour</label>
            <input type="text" name="colour" id="colour" value="<?php echo $me['colour']; ?>" required />
              <br><br>

            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="saveChanges">Save Changes</button>
            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="previewChanges">Preview Changes</button>
          </div>
        </form>

        <form enctype="multipart/form-data" id="updateBio" method="POST">
          <div>
            <label for="title">Profile Picture</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="500000" />
            <input name="profilepic" type="file" style="font-size: 15px; margin: 5px 0 0;"/>
              <br><br>

            <label for="title">Header Image</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <input name="headerpic" type="file" style="font-size: 15px; margin: 5px 0 0;"/>
              <br><br>

            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="savePicture">Save Changes</button>
            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="previewPicture">Preview Changes</button>
          </div>
        </form>

      <?php } else {
        // NO ADMIN LOGGED IN
        echo "<div class='adminnotice'><span class='notice'>You shouldn't be here. Please login.</span></div>";
      } //end login check ?>
    </div>
  </body>
</html>
