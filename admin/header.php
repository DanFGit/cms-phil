<?php

$settings_sql = "SELECT * FROM settings";

try {
  $settings_stmt = $db->prepare($settings_sql);
  $settings_stmt->execute();
} catch (PDOException $e) {
  echo "Database Error: " . $e->getMessage();
}

if($settings_stmt->rowCount()==1) {
  $me = $settings_stmt->fetch(PDO::FETCH_ASSOC);

  if(isset($_POST['action']) && $_POST['action'] == "previewChanges"){
    $me['colour'] = $_POST['colour'];
    $me['forename'] = $_POST['forename'];
    $me['skills'] = $_POST['skills'];
    $me['about'] = str_replace(array("\r\n", "\n\r", "\n", "\r"), "<br>", $_POST['about']);
    $me['surname'] = $_POST['surname']; ?>
    <header style="border-bottom: #<?php echo $me['colour']; ?> solid 2px;">
      <div id="header_image">
        <a href="index.php"><img src="../img/me.jpg" alt="<?php echo $me['forename'] . ' ' . $me['surname']; ?>"></a>
      </div>

      <div id="header_name" style="color: #<?php echo $me['colour']; ?>">
        <a href="index.php"><span id="header_fname"><?php echo strtolower($me['forename']); ?></span><span id="header_sname"><?php echo strtolower($me['surname']); ?></span></a>
      </div>



      <div id="header_roles">
        <?php
        $pre = str_replace(array("\r\n", "\n\r", "\n", "\r"), "/", $me['skills']);
        $skills = explode('/', $pre);

        for($i = 0, $size = count($skills); $i < $size; $i++){
          echo "<span class='role'>" . $skills[$i] . "</span>";
        }
        ?>
      </div>
    </header>

    <div id="aboutPreview"><h1>About Me</h1> <?php echo $me['about']; ?></div>
  <?php } else if(isset($_POST['action']) && $_POST['action'] == "previewPicture"){


    if($_FILES["profilepic"]["error"] == 0){
      $target_dir = "../img/";
      $target_file = $target_dir . "preview.jpg";

      $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

      $imageCheck = getimagesize($_FILES["profilepic"]["tmp_name"]);
      if($imageCheck == false) {
        //fileupload is not an image
      } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
        //fileupload is not a supported image type
      } else {
        //fileupload is an image
        if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
          //file successfully uploaded
        } else {
          //something went wrong
        }
      }
    }
    if($_FILES["headerpic"]["error"] == 0){
      $header_target_dir = "../img/";
      $header_target_file = $header_target_dir . "preview_header.png";

      $header_imageFileType = pathinfo($header_target_file, PATHINFO_EXTENSION);

      $header_imageCheck = getimagesize($_FILES["headerpic"]["tmp_name"]);
      if($header_imageCheck == false) {
        //fileupload is not an image
      } elseif($header_imageFileType != "jpg" && $header_imageFileType != "png" && $header_imageFileType != "jpeg" && $header_imageFileType != "gif" ) {
        //fileupload is not a supported image type
      } else {
        //fileupload is an image
        if (move_uploaded_file($_FILES["headerpic"]["tmp_name"], $header_target_file)) {
          //file successfully uploaded
        } else {
          //something went wrong
        }
      }
    }


    ?>
    <header style="border-bottom: #<?php echo $me['colour']; ?> solid 2px; background-image: url('<?php if($_FILES["headerpic"]["error"] == 0){ echo $header_target_file; } else { echo "../img/bg_header.png"; } ?>');">
      <div id="header_image">
        <a href="index.php"><img src="<?php if($_FILES["profilepic"]["error"] == 0){ echo $target_file; } else { echo "../img/me.jpg"; } ?>" alt="<?php echo $me['forename'] . ' ' . $me['surname']; ?>"></a>
      </div>

      <div id="header_name" style="color: #<?php echo $me['colour']; ?>">
        <a href="index.php"><span id="header_fname"><?php echo strtolower($me['forename']); ?></span><span id="header_sname"><?php echo strtolower($me['surname']); ?></span></a>
      </div>



      <div id="header_roles">
        <?php
        $pre = str_replace(array("\r\n", "\n\r", "\n", "\r"), "/", $me['skills']);
        $skills = explode('/', $pre);

        for($i = 0, $size = count($skills); $i < $size; $i++){
          echo "<span class='role'>" . $skills[$i] . "</span>";
        }
        ?>
      </div>
    </header>

    <div id="aboutPreview"><h1>About Me</h1> <?php echo $me['about']; ?></div>
  <?php } ?>

    <header style="height:78px; border-bottom: #<?php echo $me['colour']; ?> solid 2px;">
      <div id="header_name" style="color: #<?php echo $me['colour']; ?>;">
        <span id="header_fname"><a href="index.php">admin</a></span>
      </div>
    </header>

  <?php
} else {
  echo "Settings have not been setup correctly, please reinstall software.";
}
?>
