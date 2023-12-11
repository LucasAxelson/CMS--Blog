<?php 

function createUser() {
  global $conn;

  $img_name = $_FILES['user_image']['name'];
  $img_location = $_FILES['user_image']['tmp_name'];

  if(verifyText($_POST['user_legal_name'])) {
    $username = trim_input($_POST['user_username']);
    $legal_name = trim_input($_POST['user_legal_name']);
    $email = trim_input($_POST['user_email']);
    $status = trim_input($_POST['user_status']);
  
    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "../includes/img/user/$img_name");
  
      $stmt = "INSERT INTO users (user_username, user_legal_name, user_email, user_status_id, user_image, user_created) VALUES ('$username', '$legal_name', '$email', '$status', '$img_name',  NOW())";
  
    } else {
      $stmt = "INSERT INTO users (user_username, user_legal_name, user_email, user_status_id, user_created) VALUES ('$username', '$legal_name', '$email', '$status', NOW())";
    }

      $query = $conn->prepare($stmt);
      $query->execute();
  }
}

function deleteUser() {
  global $conn;
  $user_id = $_GET['delete'];

  try {
    $query = $conn->prepare("DELETE FROM users WHERE user_id = $user_id");
    $query->execute();
    header("Location:index.php?source=view_all_users");   
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}

function declareUsers() {
  global $conn;

  $query = $conn->prepare("SELECT * FROM users, status WHERE status.status_id = users.user_status_id");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    $items = explode(" ", $row['user_created']);
    $itemsDate = explode("-", $items[0]);
    $created = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 
  
    echo "          
     <tr>
        <td>" . $row['user_id'] . "</td>
        <td>" . $row['user_username'] . "</td>
        <td>" . $row['user_legal_name'] . "</td>
        <td>" . $row['user_email'] . "</td>
        <td>" . $row['status_name'] . "</td>
        <td>" . $created . "</td>
        <td><img width=\"100px\" src=\"../includes/img/user/" . $row['user_image'] . "\" alt=\"" . $row['user_image'] . "\"></td>
        <td><a class=\"btn btn-success\" href='index.php?source=view_all_users&approve=" . $row['user_id'] . "'>Approve</a></td>
        <td><a class=\"btn btn-danger\" href='index.php?source=view_all_users&reject=" . $row['user_id'] . "'>Reject</a></td>
        <td><a class=\"btn btn-danger\" href='index.php?source=view_all_users&delete=" . $row['user_id'] . "'>Delete</a></td>
        <td><a class=\"btn btn-info\" href='index.php?source=edit_user&edit=" . $row['user_id'] . "'>Edit</a></td>
     </tr>";
  }
}

function approveUser() {
  global $conn;
  $user_id = $_GET['approve'];

  $query = $conn->prepare("UPDATE users SET user_status_id = '4' WHERE user_id = $user_id");
  $query->execute();
}

function rejectUser() {
  global $conn;
  $user_id = $_GET['reject'];

  $query = $conn->prepare("UPDATE users SET user_status_id = '3' WHERE user_id = $user_id");
  $query->execute();
}

function listUsers () {
  global $conn;

  $query = $conn->prepare("SELECT user_id, user_username FROM users");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo " 
    <option value=\"" . $row['user_id'] . "\">" . $row['user_username'] . "</option>
    ";
  }
}

// Display comment you're about to edit
function seeUser() {
  global $conn;
  
  if(isset($_GET['edit'])) {
    $user_id = $_GET['edit'];
  

  $query = $conn->prepare("SELECT * FROM users, status WHERE status.status_id = users.user_status_id AND users.user_id = $user_id");
  $query->execute();
  

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
    
    $items = explode(" ", $row['user_created']);
    $itemsDate = explode("-", $items[0]);
    $created = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 
  
    echo "          
     <tr>
        <td>" . $row['user_id'] . "</td>
        <td>" . $row['user_username'] . "</td>
        <td width=\"400px\">" . $row['user_legal_name'] . "</td>
        <td>" . $row['user_email'] . "</td>
        <td>" . $row['status_name'] . "</td>
        <td><img width=\"100px\" src=\"../includes/img/user/" . $row['user_image'] . "\" alt=\"\"></td>
        <td>" . $created . "</td>
        <td>" . $row['user_modified'] . "</td>
     </tr>";
  }
}
}


function countComments($post_id) {
  global $conn;
  $num_comments = $conn->prepare("SELECT * FROM comments WHERE comments.comment_post_id = $post_id");
  $num_comments->execute();
  $num = $num_comments->rowCount();

  $update_comments = $conn->prepare("UPDATE posts SET post_comment_count = $num WHERE post_id = $post_id");
  $update_comments->execute();
}

function approveComment() {
  global $conn;
  $comment_id = $_GET['approve'];

  $query = $conn->prepare("UPDATE comments SET comment_status_id = '4' WHERE comment_id = $comment_id");
  $query->execute();
}

function rejectComment() {
  global $conn;
  $comment_id = $_GET['reject'];

  $query = $conn->prepare("UPDATE comments SET comment_status_id = '3' WHERE comment_id = $comment_id");
  $query->execute();
}

function displayImage() {
  global $conn;

  $query = $conn->prepare("SELECT post_image FROM posts");
  $query->execute();
  
  $image = $query->fetchColumn();
  
  echo "<img width=\"100px\" src=\"../includes/img/$image\" alt=\"\">";  
}

function listPosts () {
  global $conn;

  $query = $conn->prepare("SELECT post_id, post_title FROM posts");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo " 
    <option value=\"" . $row['post_id'] . "\">" . $row['post_title'] . "</option>
    ";
  }
}

function editComment($comment_id) {
  global $conn;

  $post_id = $_POST['post_id'];

  if(verifyText($_POST['comment_content']) && verifyText($_POST['comment_author'])) {
    $author = trim_input($_POST['comment_author']);
    $content = trim_input($_POST['comment_content']);
    $email = trim_input($_POST['comment_email']);

    $stmt = "UPDATE comments 
    SET comment_post_id = '$post_id', comment_email = '$email', comment_content = '$content', comment_author = '$author' 
    WHERE comment_id = $comment_id";
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

  if(verifyText($_POST['comment_content']) && verifyText($_POST['comment_author'])) {
    $author = trim_input($_POST['comment_author']);
    $content = trim_input($_POST['comment_content']);
    $email = trim_input($_POST['comment_email']);
  
    $stmt = "INSERT INTO comments (comment_post_id, comment_email, comment_content, comment_author, comment_status_id, comment_date) VALUES ('$post_id', '$email', '$content', '$author', '1' , NOW())";

    if(isset($_GET['reply'])) { 
      $reply_id = $_GET['reply']; 
      $stmt = "INSERT INTO comments (comment_post_id, comment_reply_id, comment_email, comment_content, comment_author, comment_status_id, comment_date) VALUES ('$post_id', '$reply_id', '$email', '$content', '$author', '1' , NOW())";    
    }

    try {
      $query = $conn->prepare($stmt);
      $query->execute();
      header("Location:index.php?source=view_all_comments");  
    } catch(PDOException $e) {
      echo "". $e->getMessage();
    }
  }
}

// Display comment you're about to edit
function seeComment() {
  global $conn;
  
  $comment_id = $_GET['edit'];
  
  $query = $conn->prepare("SELECT * FROM comments, status, posts WHERE status.status_id = comments.comment_status_id AND posts.post_id = comments.comment_post_id AND comments.comment_id = $comment_id");
  $query->execute();
  
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
    
    $items = explode(" ", $row['comment_date']);
    $itemsDate = explode("-", $items[0]);
    $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 
  
    echo "          
     <tr>
        <td>" . $row['comment_id'] . "</td>
        <td>" . $row['post_title'] . "</td>
        <td width=\"400px\">" . $row['comment_content'] . "</td>
        <td>" . $row['comment_author'] . "</td>
        <td>" . $row['comment_email'] . "</td>
        <td>" . $date . "</td>
        <td>" . $row['status_name'] . "</td>
        <td> Post </td>
     </tr>";
  }
}

function declareComments() {
  global $conn;

  $query = $conn->prepare("SELECT * FROM comments, status, posts WHERE status.status_id = comments.comment_status_id AND posts.post_id = comments.comment_post_id");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    $items = explode(" ", $row['comment_date']);
    $itemsDate = explode("-", $items[0]);
    $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 
  
    echo "          
     <tr>
     <td>" . $row['comment_id'] . "</td>
     <td>" . $row['post_title'] . "</td>
     <td width=\"400px\">" . $row['comment_content'] . "</td>
     <td>" . $row['comment_author'] . "</td>
     <td>" . $row['comment_email'] . "</td>
     <td>" . $date . "</td>
     <td> Post </td>
     <td>" . $row['status_name'] . "</td>
     <td><a class=\"btn btn-success\" href='index.php?source=view_all_comments&approve=" . $row['comment_id'] . "'>Approve</a></td>
     <td><a class=\"btn btn-danger\" href='index.php?source=view_all_comments&reject=" . $row['comment_id'] . "'>Reject</a></td>
     <td><a class=\"btn btn-danger\" href='index.php?source=view_all_comments&delete=" . $row['comment_id'] . "'>Delete</a></td>
     <td><a class=\"btn btn-info\" href='index.php?source=edit_comments&edit=" . $row['comment_id'] . "'>Edit</a></td>
     </tr>";
  }
}

function deleteComment() {
  global $conn;
  $id = $_GET['delete'];

  try {
    $query = $conn->prepare("DELETE FROM comments WHERE comment_id = $id");
    $query->execute();
    header("Location:index.php?source=view_all_comments");   
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}

function approvePost() {
  global $conn;
  $post_id = $_GET['approve'];

  $query = $conn->prepare("UPDATE posts SET post_status_id = '4' WHERE post_id = $post_id");
  $query->execute();
}

function rejectPost() {
  global $conn;
  $post_id = $_GET['reject'];

  $query = $conn->prepare("UPDATE posts SET post_status_id = '3' WHERE post_id = $post_id");
  $query->execute();
}

function editPost($id) {
  global $conn;

  
  $category = $_POST['post_category_id'];
  $img_name = $_FILES['post_image']['name'];
  $img_location = $_FILES['post_image']['tmp_name'];
  
  if(verifyText($_POST['post_content']) && verifyText($_POST['post_author']) && verifyText($_POST['post_title']) && verifyTags($_POST['post_tags'])) {
    $title = trim_input($_POST['post_title']);
    $author = trim_input($_POST['post_author']);
    $content = trim_input($_POST['post_content']);
    $tags = trim_input($_POST['post_tags']);    
  
    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "../includes/img/$img_name");
  
      $stmt = "UPDATE posts 
      SET post_category_id = '$category', post_title = '$title', post_author = '$author', post_image = '$img_name', post_content = '$content', post_tags = '$tags' 
      WHERE post_id = $id";
  
    } else {
      $stmt = "UPDATE posts 
      SET post_category_id = '$category', post_title = '$title', post_author = '$author', post_content = '$content', post_tags = '$tags' 
      WHERE post_id = $id";
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

  if(verifyText($_POST['post_content']) && verifyText($_POST['post_author']) && verifyText($_POST['post_title']) && verifyTags($_POST['post_tags'])) {
    $title = trim_input($_POST['post_title']);
    $author = trim_input($_POST['post_author']);
    $content = trim_input($_POST['post_content']);
    $tags = trim_input($_POST['post_tags']);    
  
    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "../includes/img/$img_name");
  
      $stmt = "INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags) VALUES ('$category', '$title', '$author', NOW(), '$img_name' , '$content', '$tags')";
  
    } else {
      $stmt = "INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_content, post_tags) VALUES ('$category', '$title', '$author', NOW(), '$content', '$tags')";
    }


      $query = $conn->prepare($stmt);
      $query->execute();
  }
}


function deletePost() {
  global $conn;
  $id = $_GET['delete'];

  try {
    $query = $conn->prepare("DELETE FROM posts WHERE post_id = $id");
    $query->execute();
    header("Location:index.php?source=view_all_posts");   
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}

function declarePosts() {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts, status, categories WHERE categories.cat_id = posts.post_category_id AND status.status_id = posts.post_status_id");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );
    countComments($row['post_id']);

    $items = explode(" ", $row['post_date']);
    $itemsDate = explode("-", $items[0]);
    $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 
  
    echo "          
     <tr>
        <td>" . $row['post_id'] . "</td>
        <td>" . $row['post_title'] . "</td>
        <td>" . $row['post_author'] . "</td>
        <td>" . $date . "</td>
        <td>" . $row['cat_title'] . "</td>
        <td>" . $row['status_name'] . "</td>
        <td><img width=\"100px\" src=\"../includes/img/" . $row['post_image'] . "\" alt=\"" . $row['post_image'] . "\"></td>
        <td>" . $row['post_tags'] . "</td>
        <td>" . $row['post_comment_count'] . "</td>
        <td><a class=\"btn btn-success\" href='index.php?source=view_all_posts&approve=" . $row['post_id'] . "'>Approve</a></td>
        <td><a class=\"btn btn-danger\" href='index.php?source=view_all_posts&reject=" . $row['post_id'] . "'>Reject</a></td>
        <td><a class=\"btn btn-danger\" href='index.php?source=view_all_posts&delete=" . $row['post_id'] . "'>Delete</a></td>
        <td><a class=\"btn btn-info\" href='index.php?source=edit_post&edit=" . $row['post_id'] . "'>Edit</a></td>
     </tr>";
  }
}

function declareCategories () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM categories");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "                            
      <tr>
        <td>" . $row['cat_id'] . "</td>
        <td>" . $row['cat_title'] . "</td>
        <td><a href='index.php?source=categories&delete=" . $row['cat_id'] . "'>Delete</a></td>
        <td><a href='index.php?source=categories&edit=" . $row['cat_id'] . "'>Edit</a></td>
      </tr>
    ";
  }
}

function listCategories () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM categories");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo " 
    <option value=\"" . $row['cat_id'] . "\">" . $row['cat_title'] . "</option>
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