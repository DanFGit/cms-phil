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
                  colour=:colour
                  WHERE id=:id";

          $updateSkills = str_replace(array("\r\n", "\n\r", "\n", "\r"), "/", $_POST['skills']);
          $updateColour = strtoupper($_POST['colour']);
          $id = 1;

          try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->bindParam(':forename', $_POST['forename'], PDO::PARAM_STR);
            $stmt->bindParam(':surname', $_POST['surname'], PDO::PARAM_STR);
            $stmt->bindParam(':skills', $updateSkills, PDO::PARAM_STR);
            $stmt->bindParam(':colour', $updateColour, PDO::PARAM_STR);
            $stmt->execute();
          } catch (PDOException $e) {
            echo 'Update failed: ' . $e->getMessage();
          }

          if($stmt->rowCount()==1) {
            echo "<div class='adminnotice'><span class='notice'>Settings updated successfully.</span></div>";

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
          //preview changes
        } ?>

        <form id="updateBio" method="POST">
          <div>
            <label for="title">First Name</label>
            <input type="text" name="forename" id="forename" value="<?php echo $me['forename']; ?>" required />
              <br><br>
            <label for="summary">Last Name</label>
            <input type="text" name="surname" id="surname" value="<?php echo $me['surname']; ?>" required />
              <br><br>
            <label for="content">Skills (put each on a new line)</label>
            <textarea rows="7" name="skills" id="skills" required><?php
              $skills = explode('/', $me['skills']);

              for($i = 0, $size = count($skills); $i < $size; $i++){
                echo $skills[$i];
                if($i != $size - 1) echo "\n";
              }
            ?></textarea>
              <br><br>
            <label for="image">Colour</label>
            <input type="text" name="colour" id="colour" value="<?php echo $me['colour']; ?>" required />
              <br><br>

            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="saveChanges">Save Changes</button>
            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="previewChanges">Preview Changes</button>
          </div>
        </form>

        <form id="updateBio" method="POST">
          <div>
            <label for="title">Profile Picture</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
            <input name="userfile" type="file" style="font-size: 15px; margin: 5px 0 0;"/>
              <br><br>

            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="savePicture">Save Picture</button>
            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="previewPicture">Preview Picture</button>
          </div>
        </form>

        <form id="updateBio" method="POST">
          <div>
            <label for="title">Header Image</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
            <input name="userfile" type="file" style="font-size: 15px; margin: 5px 0 0;"/>
              <br><br>

            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="saveHeader">Save Header Image</button>
            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="previewHeader">Preview Header Image</button>
          </div>
        </form>

        <?php if($_POST['action'] == "savePicture") {
          var_dump($_POST);
          var_dump($_FILES);
        } ?>

      <?php } else {
        // NO ADMIN LOGGED IN
        echo "<div class='adminnotice'><span class='notice'>You shouldn't be here. Please login.</span></div>";
      } //end login check ?>
    </div>
  </body>
</html>
