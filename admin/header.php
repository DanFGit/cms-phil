<?php

//Admin is previewing homepage text changes
if(isset($_POST['action']) && $_POST['action'] == "previewChanges"){

  $me['colour'] = $_POST['colour'];
  $me['forename'] = $_POST['forename'];
  $me['skills'] = $_POST['skills'];
  $me['about'] = str_replace(array("\r\n", "\n\r", "\n", "\r"), "<br>", $_POST['about']);
  $me['surname'] = $_POST['surname'];

}

//Admin is previewing homepage image changes
if(isset($_POST['action']) && $_POST['action'] == "previewPicture"){

  //Upload the preview images if set
  if($_FILES["profilepic"]["error"] == 0){
    $target_dir = "../img/upload/";
    $target_file = $target_dir . "preview.jpg";

    $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);

    $imageCheck = getimagesize($_FILES["profilepic"]["tmp_name"]);
    if($imageCheck == false) {
      //Upload is not an image
    } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
      //Upload is not a supported image type
    } else {
      //Upload is a supported image
      if (move_uploaded_file($_FILES["profilepic"]["tmp_name"], $target_file)) {
        //Image successfully uploaded
      } else {
        //Something went wrong
      }
    }
  }
  if($_FILES["headerpic"]["error"] == 0){
    $header_target_dir = "../img/upload/";
    $header_target_file = $header_target_dir . "preview_header.png";

    $header_imageFileType = pathinfo($header_target_file, PATHINFO_EXTENSION);

    $header_imageCheck = getimagesize($_FILES["headerpic"]["tmp_name"]);
    if($header_imageCheck == false) {
        //Upload is not an image
    } elseif($header_imageFileType != "jpg" && $header_imageFileType != "png" && $header_imageFileType != "jpeg" && $header_imageFileType != "gif" ) {
        //Upload is not a supported image type
    } else {
        //Upload is an image
      if (move_uploaded_file($_FILES["headerpic"]["tmp_name"], $header_target_file)) {
        //Image successfully uploaded
      } else {
        //Something went wrong
      }
    }
  }
}

if(isset($_POST['action']) && ($_POST['action'] == "previewChanges" || $_POST['action'] == "previewPicture")) { ?>

  <header style="border-bottom: #<?php echo $me['colour']; ?> solid 2px; background-image: url('<?php if(isset($_FILES['headerpic']) && $_FILES["headerpic"]["error"] == 0){ echo $header_target_file; } else { echo "../img/upload/bg_header.png"; } ?>');">
    <div id="header_image">
      <a href="index.php"><img src="<?php if(isset($_FILES['profilepic']) && $_FILES["profilepic"]["error"] == 0){ echo $target_file; } else { echo "../img/upload/me.jpg"; } ?>" alt="<?php echo $me['forename'] . ' ' . $me['surname']; ?>"></a>
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

  <div id="aboutPreview">
    <h1>About Me</h1>
    <?php echo $me['about']; ?>
  </div>
<?php } ?>

<!-- admin header -->
<header style="height:78px; border-bottom: #<?php echo $me['colour']; ?> solid 2px;">
  <div id="header_name" style="color: #<?php echo $me['colour']; ?>;">
    <span id="header_fname"><a href="index.php">admin</a></span>
  </div>
</header>
