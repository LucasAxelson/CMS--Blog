<?php 


function pullItem($item, $where = "") {
  global $conn;

  $query = $conn->prepare(selectStatement($item, $where));
  $query->execute();

  while ( $row = $query->fetch(PDO::FETCH_ASSOC ) ) { 
    extract($row);

    $array = tempArray();

    if(str_contains($item, "users")) {
      $array["username"] = $row["user_username"];
      $array["legal_name"] = $row["user_legal_name"];
      $array["email"] = $row["user_email"];
      $array["image"] = $row["user_image"];

    } else if (str_contains($item, "comments")) {
      $array["email"] = $row["comment_email"];
      $array["content"] = $row["comment_content"];

    } else if (str_contains($item, "posts")) {
      $array["title"] = $row["post_title"];
      $array["content"] = $row["post_content"];
      $array["tags"] = $row["post_tags"];
    }

    return $array;
  }
}

function seeSelectedItem($item) {
  $id = $_POST['selected_id'];
  $stmt = "Location: index.php?source";
  if($item == "posts") { $stmt .= "=edit_post&edit=$id";}
  if($item == "users") { $stmt .= "=edit_user&edit=$id";}
  if($item == "comments") { $stmt .= "=edit_comments&edit=$id";}
  
  return header($stmt);
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

    if(isset($_FILES['account_image']['name'])) {
      // global $file_name, $array_img;
  
      // Establish path to directory
      $img_dir = "includes/img/user/";
  
      // Include file in path to check for files with the same name
      $img_dir_new = $img_dir . basename($_FILES['account_image']['name']);
      
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
        if(move_uploaded_file($_FILES['account_image']['tmp_name'], $img_dir)) {
          $user['user_image'] = $_FILES['account_image']['name']; 
        }
      } else if ($uploadOk == 1 && file_exists($img_dir_new))  { 
        if(move_uploaded_file($_FILES['account_image']['tmp_name'], $img_dir_rnd)) {
          $user['user_image'] = $file_name;
        }
      }
    }
  
    
    $stmt = newUserStatement("edit", $user, $id);

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
    header("Location:index.php?source=view_all_users");  
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  }
  
}

function createUser() {
  global $conn;

  $img_name = $_FILES['user_image']['name'];
  $img_location = $_FILES['user_image']['tmp_name'];

  if(verifyText($_POST['user_legal_name']) && verifyEmail($_POST['user_email'])) {
    $username = trim_input($_POST['user_username']);
    $legal_name = trim_input($_POST['user_legal_name']);
    $email = trim_input($_POST['user_email']);
    $status = trim_input($_POST['user_status']);
    $access = trim_input($_POST['user_access']);
    
    $password = trim_input($_POST['user_password']);
    $password = password_hash($password, PASSWORD_DEFAULT);

    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "../includes/img/user/$img_name");
      $stmt = userStatement("add", $username, $legal_name, $email, $password, $status, $access, $img_name, "", "yes");
  
    } else {
      $stmt = userStatement("add", $username, $legal_name, $email, $password, $status, $access, $img_name, "", "no");
    }

      $query = $conn->prepare($stmt);
      $query->execute();
  }
}

function declareUsers() {
  global $conn;

  $query = $conn->prepare(selectStatement("users, status, access", "status.status_id = users.user_status_id AND access.access_id = users.user_access_id"));
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
  
    echo "          
     <tr>
        <td class=\"td-style\">" . $row['user_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['user_username'] . "</td>
        <td class=\"td-style\">" . $row['user_legal_name'] . "</td>
        <td class=\"td-style\">" . $row['user_email'] . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-style\">" . $row['access_title'] . "</td>
        <td class=\"td-style\">" . dateTime($row['user_created'], "date") . "</td>
        <td class=\"td-image-div\"><img class=\"td-image\" src=\"../includes/img/user/" . $row['user_image'] . "\" alt=\"" . $row['user_image'] . "\"></td>
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
        <td>" . $row['user_id'] . "</td>
        <td>" . $row['user_username'] . "</td>
        <td width=\"400px\">" . $row['user_legal_name'] . "</td>
        <td>" . $row['user_email'] . "</td>
        <td>" . $row['status_name'] . "</td>
        <td class=\"td-image-div\"><img width=\"100px\" src=\"../includes/img/user/" . $row['user_image'] . "\" alt=\"\"></td>
        <td>" . dateTime($row['user_created'], "date") . "</td>
        <td>" . $row['user_modified'] . "</td>
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

function displayImage($table, $image_column, $where_column) {
  global $conn;
  
  $id = $_GET['edit'];
  $query = $conn->prepare("SELECT $image_column FROM $table WHERE $where_column = $id");
  $query->execute();
  
  $image = $query->fetchColumn();
  
  echo "<img width=\"100px\" src=\"../includes/img/$image\" alt=\"\">";  
}

function editComment($comment_id) {
  global $conn;

  $post_id = $_POST['post_id'];

  if(verifyText($_POST['comment_content']) && verifyEmail($_POST['comment_email'])) {
    $author = trim_input($_POST['comment_author']);
    $content = trim_input($_POST['comment_content']);

    $stmt = commentStatement("edit", $post_id, 0, $author, $content, "no", $comment_id);
  }

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
    header("Location:index.php?source=view_all_comments");  
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  } 
}

function createComment() {
  global $conn;
  $post_id = $_POST['post_id'];

  if(verifyText($_POST['comment_content']) && verifyEmail($_POST['comment_email'])) {
    $author = trim_input($_POST['comment_author']);
    $content = trim_input($_POST['comment_content']);
    $reply_id = $_GET['reply']; 

    if(isset($_GET['reply'])) { 
      $stmt = commentStatement("add", $post_id, $reply_id, $author, $content, 'no', 0);
    } else {
      $stmt = commentStatement("add", $post_id, $reply_id, $author, $content, 'yes', 0);
    } 
  }

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
    header("Location:index.php?source=view_all_comments");  
  } catch(PDOException $e) {
    echo "". $e->getMessage();
  }
}


// Display comment you're about to edit
function seeComment() {
  global $conn;
  $comment_id = $_GET['edit'];
  
  $query = $conn->prepare
  (
  selectStatement(
    "comments, status, posts, users", 
    "status.status_id = comments.comment_status_id AND posts.post_id = comments.comment_post_id AND comments.comment_id = $comment_id AND users.user_id = comments.comment_author_id"
    )
);
  $query->execute();
  
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
  
    echo "          
     <tr>
        <td>" . $row['comment_id'] . "</td>
        <td>" . $row['post_title'] . "</td>
        <td width=\"400px\">" . $row['comment_content'] . "</td>
        <td>" . $row['user_username'] . "</td>
        <td>" . $row['user_email'] . "</td>
        <td>" . dateTime($row['comment_date'], "date") . "</td>
        <td>" . $row['status_name'] . "</td>
        <td> Post </td>
     </tr>";
  }
}

function declareComments() {
  global $conn;

  $query = $conn->prepare(
    selectStatement(
      "comments, status, posts, users", 
      "status.status_id = comments.comment_status_id AND posts.post_id = comments.comment_post_id AND users.user_id = comments.comment_author_id"
      )
  );
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

  
    echo "          
     <tr>
     <td class=\"td-style\">" . $row['comment_id'] . "</td>
     <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
     <td class=\"td-style td-content\">" . $row['comment_content'] . "</td>
     <td class=\"td-style\">" . $row['user_username'] . "</td>
     <td class=\"td-style\">" . $row['user_email'] . "</td>
     <td class=\"td-style\">" . dateTime($row['comment_date'], "date") . "</td>
     <td class=\"td-style\">" . $row['comment_reply_id'] . "</td>
     <td class=\"td-style\">" . $row['status_name'] . "</td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_comments&approve=" . $row['comment_id'] . "'>Approve</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_comments&reject=" . $row['comment_id'] . "'>Reject</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_comments&delete=" . $row['comment_id'] . "'>Delete</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_comments&edit=" . $row['comment_id'] . "'>Edit</a></td>
     </tr>";
  }
}

function editPost($id) {
  global $conn;

  
  $category = $_POST['post_category_id'];
  $img_name = $_FILES['post_image']['name'];
  $img_location = $_FILES['post_image']['tmp_name'];
  
  if(verifyText($_POST['post_content']) && verifyText($_POST['post_title']) && verifyTags($_POST['post_tags'])) {
    $title = trim_input($_POST['post_title']);
    $author = trim_input($_POST['post_author']);
    $content = trim_input($_POST['post_content']);
    $tags = trim_input($_POST['post_tags']);    
  
    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "../includes/img/$img_name");
      $stmt = postStatement("edit", $category, $title, $author, $content, $tags, $img_name, "yes", $id);
    } else {
      $stmt = postStatement("edit", $category, $title, $author, $content, $tags, $img_name, "no", $id);
    }

  }

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
    header("Location:index.php?source=view_all_posts");  
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  }
  
}

function createPost() {
  global $conn;

  $category = $_POST['post_category_id'];
  $img_name = $_FILES['post_image']['name'];
  $img_location = $_FILES['post_image']['tmp_name'];

  if(verifyText($_POST['post_content']) && verifyText($_POST['post_title']) && verifyTags($_POST['post_tags'])) {
    $title = trim_input($_POST['post_title']);
    $author = trim_input($_POST['post_author']);
    $content = trim_input($_POST['post_content']);
    $tags = trim_input($_POST['post_tags']);    
  
    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "../includes/img/$img_name");
  
      $stmt = postStatement("add", $category, $title, $author, $content, $tags, $img_name, "yes");
  
    } else {
      $stmt = postStatement("add", $category, $title, $author, $content, $tags, $img_name, "no");
    }

      $query = $conn->prepare($stmt);
      $query->execute();
  }
}

function declarePosts() {
  global $conn;

  $stmt = selectStatement("posts, status, categories, users", "categories.cat_id = posts.post_category_id AND status.status_id = posts.post_status_id AND users.user_id = posts.post_author_id");

  $query = $conn->prepare($stmt);
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
    countComments($row['post_id']);
  
    echo "          
     <tr>
        <td class=\"td-style\">" . $row['post_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
        <td class=\"td-style\">" . $row['user_username'] . "</td>
        <td class=\"td-style\">" . dateTime($row['post_date'], "date") . "</td>
        <td class=\"td-style\">" . $row['cat_title'] . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-image-div\"><img class=\"td-image\" src=\"../includes/img/" . $row['post_image'] . "\" alt=\"" . $row['post_image'] . "\"></td>
        <td class=\"td-style\">" . $row['post_tags'] . "</td>
        <td class=\"td-style\">" . $row['post_comment_count'] . "</td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_posts&approve=" . $row['post_id'] . "'>Approve</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_posts&reject=" . $row['post_id'] . "'>Reject</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_posts&delete=" . $row['post_id'] . "'>Delete</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_post&edit=" . $row['post_id'] . "'>Edit</a></td>
     </tr>";
  }
}

function declareCategories () {
  global $conn;

  $query = $conn->prepare(selectStatement("categories", ""));
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "                            
      <tr>
        <td class=\"td-cat\">" . $row['cat_id'] . "</td>
        <td class=\"td-cat\">" . $row['cat_title'] . "</td>
        <td class=\"td-cat td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=categories&delete=" . $row['cat_id'] . "'>Delete</a></td>
        <td class=\"td-cat td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=categories&edit=" . $row['cat_id'] . "'>Edit</a></td>
      </tr>
    ";
  }
}

function createCategory($category) {
  global $conn;

  $query = $conn->prepare("INSERT INTO categories (cat_title) VALUES ('$category')");
  $query->execute();
}

function editCategory($id, $title) {
  global $conn;

  try {
    $query = $conn->prepare("UPDATE categories SET cat_title = '$title' WHERE cat_id = $id");
    $query->execute();
    header("Location:index.php?source=categories");   
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  }
}

function deleteCategory($id) {
  global $conn;

  try {
    $query = $conn->prepare("DELETE FROM categories WHERE cat_id = $id");
    $query->execute();
    header("Location:index.phpsource=categories");   
  } catch(PDOException) {
    return false;
  }
  return true;
}

function declareError($error) {
  echo "<p>Error: $error</p>";
}
?>