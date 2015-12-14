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
  		  
        if(isset($_POST['action']) && $_POST['action'] == "deleteProject") {
          
          $sql = "DELETE FROM projects
                  WHERE id=:id";
          
          try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
            $stmt->execute();            
          } catch (PDOException $e) {
    		    echo 'Delete failed: ' . $e->getMessage();
          }
          
          if($stmt->rowCount()==1) {
            echo "Success.";
            ?><meta http-equiv="refresh" content="0"><?php
          } else {
            echo "Delete Failed.";
          }
          
        } elseif(isset($_POST['action']) && $_POST['action'] == "updateProject") {
          
          $sql = "UPDATE projects SET 
                  title=:title, 
                  summary=:summary, 
                  image=:image, 
                  content=:content 
                  WHERE id=:id";
        
          try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $_POST['id'], PDO::PARAM_STR);
            $stmt->bindParam(':title', $_POST['title'], PDO::PARAM_STR);
            $stmt->bindParam(':summary', $_POST['summary'], PDO::PARAM_STR);
            $stmt->bindParam(':image', $_POST['image'], PDO::PARAM_STR);
            $stmt->bindParam(':content', $_POST['content'], PDO::PARAM_STR);
            $stmt->execute();
          } catch (PDOException $e) {
    		    echo 'Update failed: ' . $e->getMessage();
          }
          
          if($stmt->rowCount()==1) {
            echo "Success.";
            ?><meta http-equiv="refresh" content="0"><?php
          } else {
            echo "Update Failed.";
          }
          
        } elseif(isset($_GET['id'])) {
          /* SPECIFIC PROJECT VIEW
           * get project info
           * input values into form
           */
          $sql = "SELECT * FROM projects WHERE id=:id";
        
          try {
            $stmt = $db->prepare($sql);
            $stmt->bindParam(':id', $_GET['id'], PDO::PARAM_STR);
            $stmt->execute();
          } catch (PDOException $e) {
    		    echo 'Could not fetch project list: ' . $e->getMessage();
          }
          
          if($stmt->rowCount()==1) {
            $result = $stmt->fetch(PDO::FETCH_ASSOC); ?>
            
          
              <form id="updateProject" method="POST">
                <div>
                  <input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" required />
                  <input type="hidden" name="action" value="updateProject" />
                  <label for="title">Title</label>
                  <input type="text" name="title" id="title" value="<?php echo $result['title']; ?>" required />
                    <br><br>
                  <label for="summary">Summary</label>
                  <textarea rows="5" name="summary" id="summary" required><?php echo $result['summary']; ?></textarea>
                    <br><br>
                  <label for="content">Content</label>
                  <textarea rows="10" name="content" id="formcontent" required><?php echo $result['content']; ?></textarea>
                    <br><br>
                  <label for="image">Image URL</label>
                  <input type="text" name="image" id="image" value="<?php echo $result['image']; ?>" required />
                    <br><br>
                  
                  <button>Save Changes</button>
                  <button>Preview Changes</button>
                </div>
              </form>
          
          <?php } else {
            echo "No such project with id " . $_GET['id'] . ".";
          } 
          
        } else {
          //show list of projects
          //allow new project addition (hidden by default)
          $sql = "SELECT * FROM projects";
          
          ?><div class="adminnotice"><?php
          try {
            $stmt = $db->prepare($sql);
            $stmt->execute();
            echo "<span class='notice'>Below is a list of projects displayed on the side. You can edit, delete, hide and reorder them from this page.</span>";
          } catch (PDOException $e) {
    		    echo 'Could not fetch project list: ' . $e->getMessage();
          }
          ?></div><?php
          
          if($stmt->rowCount()>0) {
            for($i = 0; $i < $stmt->rowCount(); $i++){ 
              
              $result = $stmt->fetch(PDO::FETCH_ASSOC); ?> 
              <div class="project">
                <span class="title"><?php echo $result['title']; ?></span>
                <a class="button" href="?id=<?php echo $result['id']; ?>">Down</a>
                <a class="button" href="?id=<?php echo $result['id']; ?>">Up</a>
                <form method="POST" class="deleteForm">
                  <input type="hidden" name="id" value="<?php echo $result['id']; ?>" />                    
                  <input type="hidden" name="action" value="deleteProject" />
                  <button>Delete</button>
                </form>
                 <!-- <a class="button delete" href="?id=<?php echo $result['id']; ?>">Delete</a>-->
                <a class="button" href="?id=<?php echo $result['id']; ?>">Hide</a>
                <a class="button" href="?id=<?php echo $result['id']; ?>">Edit</a>
              </div> <?php
            }
          } else {
            echo "No projects available OR unable to fetch project list.";
          } ?>
          <br><br><br><a href="new.php">Create New Project</a><?php
        }
        
      } else { 
        // NO ADMIN LOGGED IN
        echo "You shouldn't be here.";
  	  
      } //end login check ?>
    </div>
  </body>
</html>
