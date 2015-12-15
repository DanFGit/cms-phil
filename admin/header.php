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
