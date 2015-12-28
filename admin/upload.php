<?php
  //Connect to database and start session
  include_once('../common/base.php');

  //Login Check
  $loggedIn = (isset($_SESSION['loggedin']) ? true : false);
  if($loggedIn) {

    //Upload form submitted
    if(isset($_POST['action']) && $_POST['action'] == 'uploadImage') {

      //A file has been uploaded successfully, but temporarily
      if($_FILES["imageupload"]["error"] == 0){
        $target_dir = "../img/upload/";
        $target_file = $target_dir . basename($_FILES["imageupload"]["name"]);

        $imageFileType = pathinfo($target_file, PATHINFO_EXTENSION);
        $success = true;

        //Check if file is a supported image
        if(getimagesize($_FILES["imageupload"]["tmp_name"])) {
          $success = false;
          $notices .= "<div class='adminnotice'><span class='notice'>Sorry, you need to upload an image! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
        } elseif($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
          $success = false;
          $notices .= "<div class='adminnotice'><span class='notice'>Sorry, only JPG, JPEG, PNG and GIF files are supported! You uploaded a " . strtoupper($imageFileType) . " file.</span></div>";
        }

        //Check if file already exists
        if(file_exists($target_file)) {
          $success = false;
          $notices .= "<div class='adminnotice'><span class='notice'>Sorry, '" . basename($_FILES["imageupload"]["name"]) . "' already exists. Rename your file then reupload it!</span></div>";
        }

        // Check file size
        if ($_FILES["imageupload"]["size"] > 2000000) {
          $success = false;
          $notices .= "<div class='adminnotice'><span class='notice'>Sorry, Your file must be smaller than 2MB!</span></div>";
        }

        //All validation checks passed
        if($success) {
          if (move_uploaded_file($_FILES["imageupload"]["tmp_name"], $target_file)) {
            $notices .= "<div class='adminnotice'><span class='success'>Your image has been uploaded. It's URL is 'img/upload/" . basename($_FILES["imageupload"]["name"]) . "'</span></div>";
          } else {
            $notices .= "<div class='adminnotice'><span class='notice'>Sorry, there was an error uploading your file.</span></div>";
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
    if($loggedIn) include_once("nav.php");
    ?>

    <div id="content">

      <?php if($loggedIn) {
        //Admin is logged in

        if(isset($notices)) echo $notices; ?>

        <form enctype="multipart/form-data" id="uploadImage" method="POST">
          <div>
            <label for="imageupload">Upload Image</label>
            <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
            <input name="imageupload" type="file" style="font-size: 15px; margin: 5px 0 0;"/>
                <br><br>

            <button style="border: 1px solid #<?php echo $me['colour']; ?>;" type="submit" name="action" value="uploadImage">Save Post</button>
          </div>
        </form>

      <?php } else {
        //Admin isn't logged in ?>

        <form id="login-form" action="index.php?redirect=upload" method="POST">
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
    </div><!--end content -->

    <!--Import jQuery-->
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
  </body>
</html>
