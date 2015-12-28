<?php
  //Connect to database and start session
  include_once('../common/base.php');

  //All database queries
  if(isset($_SESSION['loggedin'])) {
    if(isset($_POST['action'])){
      if($_POST['action'] == "saveChanges"){
        $forenameTo = $_POST['forename'];
        $surnameTo = $_POST['surname'];
        $skillsTo = str_replace(array("\r\n", "\n\r", "\n", "\r"), "/", $_POST['skills']);
        $aboutTo = str_replace(array("\r\n", "\n\r", "\n", "\r"), "<br>", $_POST['about']);
        $colourTo = strtoupper($_POST['colour']);
        $id = 1;

        $updateSettingsSQL = "UPDATE settings SET forename=:forename, surname=:surname, skills=:skills, about=:about, colour=:colour WHERE id=:id";

        try {
          $updateSettings = $db->prepare($updateSettingsSQL);
          $updateSettings->bindParam(':id', $id, PDO::PARAM_STR);
          $updateSettings->bindParam(':forename', $forenameTo, PDO::PARAM_STR);
          $updateSettings->bindParam(':surname', $surnameTo, PDO::PARAM_STR);
          $updateSettings->bindParam(':skills', $skillsTo, PDO::PARAM_STR);
          $updateSettings->bindParam(':about', $aboutTo, PDO::PARAM_STR);
          $updateSettings->bindParam(':colour', $colourTo, PDO::PARAM_STR);
          $updateSettings->execute();
        } catch (PDOException $e) {
          $notices.= "<div class='adminnotice'><span class='notice'>Database Error: " . $e->getMessage() . "</span></div>";
        }

        if($updateSettings->rowCount()==1) {
          $notices .= "<div class='adminnotice'><span class='success'>Your information has been successfully updated.</span></div>";
          $me['forename'] = $forenameTo;
          $me['surname'] = $surnameTo;
          $me['skills'] = $skillsTo;
          $me['about'] = $aboutTo;
          $me['colour'] = $colourTo;
        } else {
          $notices .= "<div class='adminnotice'><span class='notice'>Update Failed. Did you actually change anything?</span></div>";
        }
      }

      if($_POST['action'] == "previewChanges"){
        $notices .= "<div class='adminnotice'><span class='notice'>You have unsaved changes! Click Save Changes to apply them.</span></div>";
      }

      if($_POST['action'] == "previewPicture"){
        $notices .= "<div class='adminnotice'><span class='notice'>Happy with your changes?

          <form id='saveChanges' method='POST'>
            <div>
              <input type='hidden' name='profileChange' value='" . $_FILES['profilepic']['error'] ."' />
              <input type='hidden' name='headerChange' value='" . $_FILES['headerpic']['error'] . "' />
              <button style='border: 1px solid #" . $me['colour'] . ";' type='submit' name='action' value='savePreview'>Click here to Save Changes</button>
            </div>
          </form>

        </span></div>";
      }

      if($_POST['action'] == "savePreview"){
        if($_POST['profileChange'] == 0) {
          if(rename("../img/upload/preview.jpg", "../img/upload/me.jpg")){
            $notices .= "<div class='adminnotice'><span class='success'>Profile Picture Updated Successfully</span></div>";
          } else {
            $notices .= "<div class='adminnotice'><span class='notice'>Profile Picture Update Failed</span></div>";
          }
        }
        if($_POST['headerChange'] == 0) {
          if(rename("../img/upload/preview_header.png", "../img/upload/bg_header.png")){
            $notices .= "<div class='adminnotice'><span class='success'>Header Updated Successfully</span></div>";
          } else {
            $notices .= "<div class='adminnotice'><span class='notice'>Header Update Failed</span></div>";
          }
        }
      }

      if($_POST['action'] == "savePicture"){
        if($_FILES["profilepic"]["error"] == 0){
          $target_dir = "../img/upload/";
          $target_file = $target_dir . "me.jpg";

          $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

          $imageCheck = getimagesize($_FILES["profilepic"]["tmp_name"]);
          if($imageCheck == false) {
            //fileupload is not an image
            $notices .= "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
          } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            //fileupload is not a supported image type
            $notices .= "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
          } else {
            //fileupload is an image
            if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
              $notices .= "<div class='adminnotice'><span class='success'>Your new profile picture has been uploaded.</span></div>";
            } else {
              $notices .= "<div class='adminnotice'><span class='notice'>Sorry, there was an error uploading your file.</span></div>";
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
            $notices .= "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
          } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
            //fileupload is not a supported image type
            $notices .= "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
          } else {
            //fileupload is an image
            if (move_uploaded_file($_FILES["headerpic"]["tmp_name"], $target_file)) {
              $notices .= "<div class='adminnotice'><span class='success'>Your new header image has been uploaded.</span></div>";
            } else {
              $notices .= "<div class='adminnotice'><span class='notice'>Sorry, there was an error uploading your file.</span></div>";
            }
          }
        }
      }
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
        if(isset($notices)) echo $notices; ?>

        <form id="updateBio" method="POST">
          <div>
            <label for="forename">First Name</label>
            <input type="text" name="forename" id="forename" value="<?php echo $me['forename']; ?>" required />
              <br><br>
            <label for="surname">Last Name</label>
            <input type="text" name="surname" id="surname" value="<?php echo $me['surname']; ?>" required />
              <br><br>
            <label for="skills">Skills <emphasis>(put each on a new line)</emphasis></label>
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
        //Admin isn't logged in ?>

        <form id="login-form" action="index.php?redirect=homepage" method="POST">
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
    </div>
  </body>
</html>
