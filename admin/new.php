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
    <header style="height:78px;">      
      <div id="header_name">
        <span id="header_fname"><a href="index.php">admin</a></span>
      </div>
    </header>
    
    <div id="content">
      
      <?php if(isset($_SESSION['loggedin'])) {
        //ADMIN IS LOGGED IN
        include "nav.php";
  		  
        if(isset($_POST['action']) && $_POST['action'] == "createProject") {
          /* NEW PROJECT CREATED
           * save to database
           */
          
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
        } else { ?>
          
          
          <form id="updateProject" method="POST">
            <div>
              <input type="hidden" name="id" required />
              <input type="hidden" name="action" value="createProject" />
              <label for="title">Title</label>
              <input type="text" name="title" id="title" required />
                <br><br>
              <label for="summary">Summary</label>
              <textarea rows="5" name="summary" id="summary" required></textarea>
                <br><br>
              <label for="content">Content</label>
              <textarea rows="10" name="content" id="content" required></textarea>
                <br><br>
              <label for="image">Image URL</label>
              <input type="text" name="image" id="image" required />
                <br><br>
              
              <button>Save Project</button>
              <button>Preview Project</button>
            </div>
          </form>
          
          <?php
          
        }
        
      } else { 
        // NO ADMIN LOGGED IN
        echo "You shouldn't be here.";
  	  
      } //end login check ?>
    </div>
  </body>
</html>
