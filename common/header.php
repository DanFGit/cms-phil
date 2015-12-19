<?php

$settings_sql = "SELECT * FROM settings";

try {
  $settings_stmt = $db->prepare($settings_sql);
  $settings_stmt->execute();
} catch (PDOException $e) {
  echo "Database Error: " . $e->getMessage();
}

if($settings_stmt->rowCount()==1) {
  $me = $settings_stmt->fetch(PDO::FETCH_ASSOC); ?>

  <header style="border-bottom: #<?php echo $me['colour']; ?> solid 2px;">
    <div id="header_image">
      <a href="index.php"><img src="img/upload/me.jpg" alt="<?php echo $me['forename'] . ' ' . $me['surname']; ?>"></a>
    </div>

    <div id="header_name" style="color: #<?php echo $me['colour']; ?>">
      <a href="index.php"><span id="header_fname"><?php echo strtolower($me['forename']); ?></span><span id="header_sname"><?php echo strtolower($me['surname']); ?></span></a>
    </div>



    <div id="header_roles">
      <?php
      $skills = explode('/', $me['skills']);

      for($i = 0, $size = count($skills); $i < $size; $i++){
        echo "<span class='role'>" . $skills[$i] . "</span>";
      }
      ?>
    </div>
  </header>

<?php
} else {
  echo "Settings have not been setup correctly, please reinstall software.";
}
?>
