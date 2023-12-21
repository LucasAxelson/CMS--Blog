<?php
function createUserComment() {
  global $conn;

  $post_id = $_GET['blog_id'];

  if(verifyText($_POST['form_content'])) {
    $author = trim_input($_POST['form_author']);
    $content = trim_input($_POST['form_content']);
    $reply_id = $_GET['reply']; 

    if(isset($_GET['reply'])) { 
      $stmt = commentStatement("add", $post_id, $reply_id, $author, $content, "yes");    
    } else {
      $stmt = commentStatement("add", $post_id, $reply_id, $author, $content, "no");    
    }

    try {
      $query = $conn->prepare($stmt);
      $query->execute();
      header("Location:index.php?source=blog_post&blog_id=" . $post_id . "");  
    } catch(PDOException $e) {
      echo "". $e->getMessage();
    }
  }
}

function createQuickComment() {
  global $conn;

  $post_id = $_GET['blog_id'];

  if(verifyText($_POST['user_comment_content']) && isset($_SESSION['user_id'])) {
    $author = $_SESSION['user_id'];
    $content = trim_input($_POST['user_comment_content']);
    $reply_id = $_GET['reply']; 

    if(isset($_GET['reply'])) { 
      $stmt = "INSERT INTO comments (comment_post_id, comment_reply_id, comment_author_id, comment_content, comment_status_id, comment_date) VALUES ('$post_id', '$reply_id', '$author', '$content', '4' , NOW())";    
    } else {
      $stmt = "INSERT INTO comments (comment_post_id, comment_author_id, comment_content, comment_status_id, comment_date) VALUES ('$post_id', '$author', '$content', '4' , NOW())";    
    }

    try {
      $query = $conn->prepare($stmt);
      $query->execute();
      header("Location:index.php?source=blog_post&blog_id=" . $post_id . "");  
    } catch(PDOException $e) {
      echo "". $e->getMessage();
    }
  }
}

function displayComments() {
  global $conn;
  $post_id = $_GET["blog_id"];

  try {
    $query = $conn->prepare(
      selectStatement(
        "comments, users", "comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id IS NULL AND users.user_id = comments.comment_author_id"
      )
    );
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   echo 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"index.php?source=profile_page&page=" . $row['user_id'] . "\">
        <img class=\"media-object comment-image\" src=\"includes/img/" . $row['user_image'] . "\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['user_username'] . "
          <small class=\"comment-date\">Created " . dateTime($row['comment_date'], "date") . " at " . dateTime($row['comment_date'], "time") . "</small>
        </h4> 
        <p>". $row['comment_content'] . "</p>
        <p><a class=\"pull-left reply-btn\" href=\"index.php?source=blog_post&blog_id=" . $post_id . "&reply=" . $row['comment_id'] . "\">Reply</a></p>
        <br>
      ";
    
      echo displayNestedComment($row['comment_id']);
      echo "</div></div>";
  }
}

function displayNestedComment($target_id) {
  global $conn;
  $post_id = $_GET["blog_id"];

  
  try {
    $query = $conn->prepare(selectStatement("comments, users", "comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id = $target_id AND users.user_id = comments.comment_author_id"));
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   echo 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"index.php?source=profile_page&page=" . $row['user_id'] . "\">
        <img class=\"comment-image\" src=\"includes/img/user/" . $row['user_image'] . "\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['user_username'] . "
          <small>" . dateTime($row['comment_date'], "date") . " at " . dateTime($row['comment_date'], "time") . "</small>
        </h4> 
        <p>". $row['comment_content'] . "</p>
      </div>
    </div>
    ";
  }
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
     <td><input class=\"td-check\" type=\"checkbox\" name=\"checkboxArray[]\" value=\"" . $row['comment_id'] . "\"></td>
     <td class=\"td-style\">" . $row['comment_id'] . "</td>
     <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
     <td class=\"td-style td-content\">" . $row['comment_content'] . "</td>
     <td class=\"td-style\">" . $row['user_username'] . "</td>
     <td class=\"td-style\">" . $row['user_email'] . "</td>
     <td class=\"td-style\">" . dateTime($row['comment_date'], "date") . "</td>
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