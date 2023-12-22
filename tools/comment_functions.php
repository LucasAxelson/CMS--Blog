<?php

function commentStatement($statement, $comment_array, $id = NULL) {
  if ($statement == "edit") {

    $stmt =  "UPDATE comments
      SET comment_modified = NOW()";
    $where = " WHERE comment_id = $id";

    foreach( $comment_array as $key => $content ) {
      $stmt.= ", $key = '$content'";
    }

    return $stmt . $where; 

  } else if ($statement == "add") {

    $insert =  "INSERT INTO comments (";
    $insert_close = "comment_created)";
    $value = " VALUES (";
    $value_close = "NOW())";

    foreach( $comment_array as $key => $content ) {
      $insert .= "$key, ";
      $value .= "'$content', ";
    }

    return $insert . $insert_close . $value . $value_close;

  }
}

function displayVisitorComments() {
  global $conn;
  $post_id = $_GET["blog_id"];

  try {
    $query = $conn->prepare(
        "SELECT * FROM comments WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id IS NULL AND comment_author_id IS NULL"
    );
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

    echo 
    "<div class=\"media\">
       <a class=\"pull-left\" href=\"" . (empty($row['user_id']) ? $_SERVER['REQUEST_URI'] : "index.php?source=profile_page&page=" . $row['user_id']) . "\">
        <img class=\"comment-image\" src=\"" . (empty($row['user_image']) ? "https://placehold.co/64x64" : "includes/img/user/" . $row['user_image'])  . "\" alt=\"\">
       </a>
       <div class=\"media-body\">
         <h4 class=\"media-heading\">" . (empty($row['user_username']) ? $row['comment_author'] : $row['user_username'])  . "
          <small class=\"comment-date\">Created: " . dateTime($row['comment_created'], "date") . " at " . dateTime($row['comment_created'], "time") . "</small>
         </h4> 
        <p>". $row['comment_content'] . "</p>
        <p><a class=\"pull-left reply-btn\" href=\"index.php?source=blog_post&blog_id=" . $post_id . "&reply=" . $row['comment_id'] . "\">Reply</a></p>
        <br>
      ";
    
      echo displayNestedComment($row['comment_id']);
      echo displayVisitorNestedComment($row['comment_id']);
      echo "</div></div>";
  }
}

function displayComments() {
  global $conn;
  $post_id = $_GET["blog_id"];

  try {
    $query = $conn->prepare(
        "SELECT * FROM comments, users WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id IS NULL AND users.user_id = comments.comment_author_id"
    );
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   echo 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"index.php?source=profile_page&page=" . $row['user_id'] . "\">
        <img class=\"media-object comment-image\" src=\"includes/img/user/" . $row['user_image'] . "\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['user_username'] . "
          <small class=\"comment-date\">Created: " . dateTime($row['comment_created'], "date") . " at " . dateTime($row['comment_created'], "time") . "</small>
        </h4> 
        <p>". $row['comment_content'] . "</p>
        <p><a class=\"pull-left reply-btn\" href=\"index.php?source=blog_post&blog_id=" . $post_id . "&reply=" . $row['comment_id'] . "\">Reply</a></p>
        <br>
      ";
    
      echo displayNestedComment($row['comment_id']);
      echo displayVisitorNestedComment($row['comment_id']);
      echo "</div></div>";
  }
}

function displayNestedComment($target_id) {
  global $conn;
  $post_id = $_GET["blog_id"];

  
  try {
    $query = $conn->prepare("SELECT * FROM comments, users WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id = $target_id AND users.user_id = comments.comment_author_id");
    $query->execute();
  

    while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

     echo 
     "<div class=\"media\">
        <a class=\"pull-left\" href=\"index.php?source=profile_page&page=" . $row['user_id'] . "\">
         <img class=\"comment-image\" src=\"includes/img/user/" . $row['user_image'] . "\" alt=\"\">
        </a>
        <div class=\"media-body\">
          <h4 class=\"media-heading\">" . $row['user_username'] . "
           <small class=\"comment-date\">Created: " . dateTime($row['comment_created'], "date") . " at " . dateTime($row['comment_created'], "time") . "</small>
          </h4> 
         <p>". $row['comment_content'] . "</p>
        </div>
     </div>
    ";
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function displayVisitorNestedComment($target_id) {
  global $conn;
  $post_id = $_GET["blog_id"];

  
  try {
    $query = $conn->prepare("SELECT * FROM comments WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id = $target_id AND comments.comment_author_id IS NULL");
    $query->execute();
  

    while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

     echo 
     "<div class=\"media\">
        <a class=\"pull-left\" href=\"" . (empty($row['user_id']) ? $_SERVER['REQUEST_URI'] : "index.php?source=profile_page&page=" . $row['user_id']) . "\">
         <img class=\"comment-image\" src=\"" . (empty($row['user_image']) ? "https://placehold.co/64x64" : "includes/img/user/" . $row['user_image'])  . "\" alt=\"\">
        </a>
        <div class=\"media-body\">
          <h4 class=\"media-heading\">" . (empty($row['user_username']) ? $row['comment_author'] : $row['user_username'])  . "
           <small class=\"comment-date\">Created: " . dateTime($row['comment_created'], "date") . " at " . dateTime($row['comment_created'], "time") . "</small>
          </h4> 
         <p>". $row['comment_content'] . "</p>
        </div>
     </div>
    ";
    }
  } catch (PDOException $e) {
    echo $e->getMessage();
  }
}

function editComment($id) {
  global $conn, $comment;
  
  $comment = array();  

    if(isset($_SESSION['user_id']) && !isset($_POST['visitor_submit'])) {
      $author_id = trim_input($_SESSION['user_id']);
      $comment['comment_author_id'] = $author_id;
    }

    if(isset($_POST['visitor_username']) && verifyText($_POST['visitor_username'])) {
      $visitor_name = trim_input($_POST['visitor_username']);
      $comment['comment_author'] = $visitor_name;
    }
    
    if(isset($_POST['visitor_email']) && verifyEmail($_POST['visitor_email'])) {
      $email = prepareEmail($_POST['visitor_email']);
      $comment['comment_email'] = $email;
    }

    if(isset($_POST['visitor_submit'])) {
      $comment['comment_status_id'] = 2;
    }
    
    $stmt = commentStatement("edit", $comment, $id);

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  } 
}

function createComment() {
  global $conn, $comment;
  
  $comment = array();  

    if(isset($_GET['blog_id'])) {
      $comment['comment_post_id'] = $_GET['blog_id'];
    }

    if(isset($_GET['reply'])) {
      $comment['comment_reply_id'] = $_GET['reply'];
    }

    if(isset($_SESSION['user_id']) && !isset($_POST['visitor_submit'])) {
      $author_id = trim_input($_SESSION['user_id']);
      $comment['comment_author_id'] = $author_id;
    }
    
    if(isset($_POST['visitor_email']) && isset($_POST['visitor_submit']) && verifyEmail($_POST['visitor_email'])) {
      $email = prepareEmail($_POST['visitor_email']);
      $comment['comment_email'] = $email;
    }

    if(isset($_POST['comment_content'])) {
      $content = trim_input($_POST['comment_content']);
      $comment['comment_content'] = $content;
    }

    if(isset($_POST['visitor_username']) && isset($_POST['visitor_submit']) && verifyText($_POST['visitor_username'])) {
      $visitor_name = trim_input($_POST['visitor_username']);
      $comment['comment_author'] = $visitor_name;
    }
    

    if(isset($_POST['visitor_submit'])) {
      $comment['comment_status_id'] = 2;
    } else {
      $comment['comment_status_id'] = 4;
    }
    
    $stmt = commentStatement("add", $comment);

  try {
    $query = $conn->prepare($stmt);
    $query->execute();
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
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
        <td class=\"td-style\">" . $row['comment_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
        <td class=\"td-style\" width=\"400px\">" . $row['comment_content'] . "</td>
        <td class=\"td-style\">" . $row['user_username'] . "</td>
        <td class=\"td-style\">" . $row['user_email'] . "</td>
        <td class=\"td-style\">" . dateTime($row['comment_date'], "date") . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-style\"> Post </td>
     </tr>";
  }
}

function declareComments() {
  global $conn;

  $query = $conn->prepare(
      "SELECT * FROM comments, status, posts, users 
      WHERE status.status_id = comments.comment_status_id 
      AND posts.post_id = comments.comment_post_id
      AND users.user_id = comments.comment_author_id");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

  
    echo "          
     <tr>
     <td><input class=\"td-check\" type=\"checkbox\" name=\"checkboxArray[]\" value=\"" . $row['comment_id'] . "\"></td>
     <td class=\"td-style\">" . $row['comment_id'] . "</td>
     <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
     <td class=\"td-style td-content\">" . $row['comment_content'] . "</td>
     <td class=\"td-style\">" . (empty($row['comment_author']) ? $row['user_username'] : $row['comment_author']) . "</td>
     <td class=\"td-style\">" . $row['user_email'] . "</td>
     <td class=\"td-style\">" . dateTime($row['comment_created'], "date") . "</td>
     <td class=\"td-style\">" . $row['comment_reply_id'] . "</td>
     <td class=\"td-style\">" . $row['status_name'] . "</td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-view\" href='../index.php?source=blog_post&blog_id=" . $row['comment_post_id'] . "'>View</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_comments&approve=" . $row['comment_id'] . "'>Approve</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_comments&reject=" . $row['comment_id'] . "'>Reject</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_comments&delete=" . $row['comment_id'] . "'>Delete</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_comments&edit=" . $row['comment_id'] . "'>Edit</a></td>
     </tr>";
  }
  declareVisitorComments();
}

function declareVisitorComments() {
  global $conn;

  $query = $conn->prepare(
      "SELECT * FROM comments, status, posts 
      WHERE status.status_id = comments.comment_status_id 
      AND posts.post_id = comments.comment_post_id
      AND comments.comment_author_id IS NULL
      ");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

  
    echo "          
     <tr>
     <td><input class=\"td-check\" type=\"checkbox\" name=\"checkboxArray[]\" value=\"" . $row['comment_id'] . "\"></td>
     <td class=\"td-style\">" . $row['comment_id'] . "</td>
     <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
     <td class=\"td-style td-content\">" . $row['comment_content'] . "</td>
     <td class=\"td-style\">" . (empty($row['comment_author']) ? $row['user_username'] : $row['comment_author']) . "</td>
     <td class=\"td-style\">" . (empty($row['comment_author']) ? $row['user_email'] : $row['comment_email']) . "</td>
     <td class=\"td-style\">" . dateTime($row['comment_created'], "date") . "</td>
     <td class=\"td-style\">" . $row['comment_reply_id'] . "</td>
     <td class=\"td-style\">" . $row['status_name'] . "</td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-view\" href='../index.php?source=blog_post&blog_id=" . $row['comment_post_id'] . "'>View</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_comments&approve=" . $row['comment_id'] . "'>Approve</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_comments&reject=" . $row['comment_id'] . "'>Reject</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_comments&delete=" . $row['comment_id'] . "'>Delete</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_comments&edit=" . $row['comment_id'] . "'>Edit</a></td>
     </tr>";
  }
}

?>