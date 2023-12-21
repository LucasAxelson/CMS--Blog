<?php

function displayNavigation () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM categories LIMIT 8");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
    
    echo "                    
    <li>
      <a href=\"index.php?source=main_page&category=" . $row['cat_id'] . "\">" . $row['cat_title'] . "</a>
    </li>
    ";
  }
}

function displayUserImage($table, $image_column, $where_column) {
  global $conn;
  
  $id = $_GET['edit'];
  $query = $conn->prepare("SELECT $image_column FROM $table WHERE $where_column = $id");
  $query->execute();
  
  $image = $query->fetchColumn();
  
  echo "<img width=\"100px\" src=\"includes/img/$image\" alt=\"\">";  
}

function showProfile() {
  global $conn;

  $page = $_GET["page"];
  $query = $conn->prepare(selectStatement("users", "user_id = $page"));
  $query->execute();
  
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "
    <div class=\"flex-row\">
      <img class=\"img-responsive user-image\" src=\"includes/img/user/" . $row['user_image'] . "\" alt=\"\">
      <h1 style=\"margin: auto;\">" .
          $row["user_username"] .
      "</h1>
    </div>
  <p style=\"text-align:center;\"><span class=\"glyphicon glyphicon-time\"></span> A Member since " . dateTime($row['user_created'], "date") . "</p>
  <hr>
  <div class=\"about-me\">
  <h4>About me: </h4>
  <p>" . $row['user_about'] . "</p>
  </div>
  <hr>";
  }  
  echo "<h4>Posts</h4>";
  showProfilePosts();
  echo "<h4>Comments</h4>";
  showProfileComments();
}

function showProfilePosts() {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts WHERE posts.post_author_id = " . $_GET["page"]);
  $query->execute();
  
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    if($row["post_author_id"] == $_SESSION['user_id']) {
      $edit = "<div>
        <a href=\"index.php?source=edit_post&edit=" . $row['post_id'] . "\">Edit</a>
      </div> ";
    } else { $edit = ""; }

    echo "
    <div class=\"flex-row\" style=\"justify-content: space-between;\">
      <div>
      <a href=\"index.php?source=blog_post&blog_id=" . $row['post_id'] . "\">" . $row["post_title"] . "</a>
      </div>" . $edit . 
    "</div>
  ";
}
}

function showProfileComments() {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts, comments WHERE comments.comment_author_id = " . $_GET["page"] . " AND comments.comment_post_id = posts.post_id");
  $query->execute();
  
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    if($row["comment_author_id"] == $_SESSION['user_id']) {
      $edit = "<div>
        <a href=\"index.php?source=edit_comment&edit=" . $row['post_id'] . "\">Edit</a>
      </div> ";
    } else { $edit = ""; }

    echo "
    <div class=\"flex-row\" style=\"justify-content: space-between;\">
      <div>  
        <a href=\"index.php?source=blog_post&blog_id=" . $row['comment_post_id'] . "\">" . $row["post_title"] . "</a>
      </div>" . $edit .
    "</div>
  ";
}
}
function userStatement($statement, $user_array, $id = NULL) {
  if ($statement == "edit") {
    global $stmt;

    $stmt =  "UPDATE users
      SET user_modified = NOW()";
    $where = " WHERE user_id = $id";

    foreach( $user_array as $key => $content ) {
      $stmt.= ", $key = '$content'";
    }

    return $stmt . $where; 

  } else if ($statement == "add") {
    global $insert, $value;

    $insert =  "INSERT INTO users (";
    $insert_close = "user_created)";
    $value = " VALUES (";
    $value_close = "NOW())";

    foreach( $user_array as $key => $content ) {
      $insert .= "$key, ";
      $value .= "'$content', ";
    }

    return $insert . $insert_close . $value . $value_close;

  }
}

function createAccount($dir = "") {
  global $conn;
  global $user;
  
  $user = array(); 

  if(isset($_POST['account_username'])) {
    $username = trim_input($_POST['account_username']);
    $user['user_username'] = $username;
  }

  if(isset($_POST['account_legal_name']) && verifyText($_POST['account_legal_name'])) {
    $legal_name = trim_input($_POST['account_legal_name']);
    $user['user_legal_name'] = $legal_name;
  }
  
  if(isset($_POST['account_email']) && verifyEmail($_POST['account_email'])) {
    $email = prepareEmail($_POST['account_email']);
    $user['user_email'] = $email;
  }

  if(isset($_POST['account_access'])) {
    $user['user_access_id'] = $_POST['account_access'];
  } else {
    $user['user_access_id'] = 2;
  }

  if(isset($_POST['account_status'])) {
    $user['user_status_id'] = $_POST['account_status'];
  } else {
    $user['user_status_id'] = 1;
  }


  if(isset($_POST['account_password'])) {
    $password = trim_input($_POST['account_password']);
    $password = password_hash($password, PASSWORD_DEFAULT);
    $user['user_password'] = $password;
  }

  if(isset($_FILES['uploaded_image']['name'])) {
    // Establish path to directory
    $img_dir = $dir . "includes/img/user/";

    // Include file in path to check for files with the same name
    $img_dir_new = $img_dir . basename($_FILES['uploaded_image']['name']);
    
    // Check if file already exists
    if (file_exists($img_dir_new)) {
      // Pull image file type, create random file name and set to be inputted into db
      $imageFileType = strtolower(pathinfo($img_dir_new, PATHINFO_EXTENSION));
      // Create random file name
      $random_string = generateRandomString(8);
      // Set new file name for db and directory
      $file_name = "$random_string.$imageFileType";
      // Set path with new file name
      $img_dir_rnd = $img_dir . basename($file_name);
    }
    
    $uploadOk = prepareImage($img_dir_new);
    if($uploadOk == 1 && !file_exists($img_dir_new)) {
      if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_new)) {
        $user['user_image'] = $_FILES['uploaded_image']['name']; 
      }
    } else if ($uploadOk == 1 && file_exists($img_dir_new))  { 
      if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_rnd)) {
        $user['user_image'] = $file_name;
      }
    }
  }

  $stmt = userStatement("add", $user);
  
  try {
    $query = $conn->prepare($stmt);
    $query->execute();
  } catch (PDOException $e) {
    echo "". $e->getMessage();
  }
}

function loginUser() {
  global $conn;

  if(verifyEmail($_POST['login_email'])) { 
    try {
      $email = strtolower($_POST['login_email']);
      $password = $_POST["login_password"];

      $query = $conn->prepare("SELECT user_email, user_password, user_id, user_username, user_access_id FROM users WHERE user_email =  '$email'");
      $query->execute();

      while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
        extract( $row );
        $db_email = $row["user_email"];
        $db_password = $row["user_password"];

        if( $email == $db_email && password_verify($password, $db_password) ) {
          $_SESSION["user_id"] = $row["user_id"];
          $_SESSION["user_username"] = $row["user_username"];
          $_SESSION["user_access_id"] = $row["user_access_id"];

          header("Location: index.php?source=main_page");
        }
      }
    } catch (PDOException $e) {
      echo "Error: ". $e->getMessage() ."";
     }
  }
} 

function logoutUser() {
  $_SESSION["user_id"] = null;
  $_SESSION["user_username"] = null;
  $_SESSION["user_access_id"] = null;
  header("Location: index.php?source=main_page");
}

function editUser($id) {
  global $conn;
  global $user;
  
  $user = array();  

    if(isset($_POST['user_username'])) {
      $username = trim_input($_POST['user_username']);
      $user['user_username'] = $username;
    }

    if(isset($_POST['user_legal_name']) && verifyText($_POST['user_legal_name'])) {
      $legal_name = trim_input($_POST['user_legal_name']);
      $user['user_legal_name'] = $legal_name;
    }
    
    if(isset($_POST['user_email']) && verifyEmail($_POST['user_email'])) {
      $email = prepareEmail($_POST['user_email']);
      $user['user_email'] = $email;
    }

    if(isset($_POST['user_access'])) {
      $access = trim_input($_POST['user_access']);
      $user['user_access_id'] = $access;
    }

    if(isset($_POST['user_status'])) {
      $status = trim_input($_POST['user_status']);
      $user['user_status_id'] = $status;
    }

    if(isset($_POST['user_password'])) {
      $password = trim_input($_POST['user_password']);
      $password = password_hash($password, PASSWORD_DEFAULT);
      $user['user_password'] = $password;
    }

    if(isset($_FILES['uploaded_image']['name'])) {
      // global $file_name, $array_img;
  
      // Establish path to directory
      $img_dir = "includes/img/user/";
  
      // Include file in path to check for files with the same name
      $img_dir_new = $img_dir . basename($_FILES['uploaded_image']['name']);
      
      // Check if file already exists
      if (file_exists($img_dir_new)) {
        // Pull image file type, create random file name and set to be inputted into db
        $imageFileType = strtolower(pathinfo($img_dir_new, PATHINFO_EXTENSION));
        // Create random file name
        $random_string = generateRandomString(8);
        // Set new file name for db and directory
        $file_name = "$random_string.$imageFileType";
        // Set path with new file name
        $img_dir_rnd = $img_dir . basename($file_name);
      }
      
      $uploadOk = prepareImage($img_dir_new);
      if($uploadOk == 1 && !file_exists($img_dir_new)) {
        if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir)) {
          $user['user_image'] = $_FILES['uploaded_image']['name']; 
        }
      } else if ($uploadOk == 1 && file_exists($img_dir_new))  { 
        if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_rnd)) {
          $user['user_image'] = $file_name;
        }
      }
    }
  
    
    $stmt = userStatement("edit", $user, $id);

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
    header("Location:index.php?source=view_all_users");  
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  }
  
}

function declareUsers() {
  global $conn;

  $query = $conn->prepare(selectStatement("users, status, access", "status.status_id = users.user_status_id AND access.access_id = users.user_access_id ORDER BY users.user_created DESC"));
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
  
    echo "          
     <tr>
        <td class=\"td-check\"><input type=\"checkbox\" name=\"checkboxArray[]\" value=\"" . $row['user_id'] . "\"></td>
        <td class=\"td-style\">" . $row['user_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['user_username'] . "</td>
        <td class=\"td-style\">" . $row['user_legal_name'] . "</td>
        <td class=\"td-style\">" . $row['user_email'] . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-style\">" . $row['access_title'] . "</td>
        <td class=\"td-style\">" . dateTime($row['user_created'], "date") . "</td>
        <td class=\"td-image-div\"><img class=\"td-image\" src=\"../includes/img/user/" . $row['user_image'] . "\" alt=\"" . $row['user_image'] . "\"></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-view\" href='../index.php?source=profile_page&page=" . $row['user_id'] . "'>View</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_users&approve=" . $row['user_id'] . "'>Approve</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_users&reject=" . $row['user_id'] . "'>Reject</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_users&delete=" . $row['user_id'] . "'>Delete</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_user&edit=" . $row['user_id'] . "'>Edit</a></td>
     </tr>";
  }
}

// Display comment you're about to edit
function seeUser() {
  global $conn;
  
  if(isset($_GET['edit'])) {
    $user_id = $_GET['edit'];
  

  $query = $conn->prepare(selectStatement("users, status", "status.status_id = users.user_status_id AND users.user_id = $user_id"));
  $query->execute();
  

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
  
    echo "          
     <tr>
        <td class=\"td-style\">" . $row['user_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['user_username'] . "</td>
        <td class=\"td-style\" width=\"400px\">" . $row['user_legal_name'] . "</td>
        <td class=\"td-style\">" . $row['user_email'] . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-image-div\"><img width=\"100px\" src=\"../includes/img/user/" . $row['user_image'] . "\" alt=\"\"></td>
        <td class=\"td-style\">" . dateTime($row['user_created'], "date") . "</td>
        <td class=\"td-style\">" . $row['user_modified'] . "</td>
     </tr>";
  }
}
}


function countComments($post_id) {
  global $conn;
  $num_comments = $conn->prepare(selectStatement("comments", "comments.comment_post_id = $post_id"));
  $num_comments->execute();
  $num = $num_comments->rowCount();

  $update_comments = $conn->prepare("UPDATE posts SET post_comment_count = $num WHERE post_id = $post_id");
  $update_comments->execute();
}

?>