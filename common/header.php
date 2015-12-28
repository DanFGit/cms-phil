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
