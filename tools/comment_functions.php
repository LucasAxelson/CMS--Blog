<?php

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
     <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_comments&approve=" . $row['comment_id'] . "'>Approve</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_comments&reject=" . $row['comment_id'] . "'>Reject</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_comments&delete=" . $row['comment_id'] . "'>Delete</a></td>
     <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_comments&edit=" . $row['comment_id'] . "'>Edit</a></td>
     </tr>";
  }
}

?>