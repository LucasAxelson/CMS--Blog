<?php

function createUserComment() {
  global $conn;

  $post_id = $_GET['blog_id'];

  if(verifyText($_POST['form_content'])) {
    $author = trim_input($_POST['form_author']);
    $content = trim_input($_POST['form_content']);
    $email = trim_input($_POST['form_email']);

    $stmt = "INSERT INTO comments (comment_post_id, comment_author_id, comment_email, comment_content, comment_status_id, comment_date) VALUES ('$post_id', '$author', '$email', '$content', '1' , NOW())";

    if(isset($_GET['reply'])) { 
      $reply_id = $_GET['reply']; 
      $stmt = "INSERT INTO comments (comment_post_id, comment_reply_id, comment_author_id, comment_email, comment_content, comment_status_id, comment_date) VALUES ('$post_id', '$reply_id', '$author', '$email', '$content', '1' , NOW())";    
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
    $query = $conn->prepare("SELECT * FROM comments, users WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id IS NULL AND users.user_id = comments.comment_author_id");
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   $openComment = 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"#\">
        <img class=\"media-object\" src=\"http://placehold.it/64x64\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['user_username'] . "
          <small>" . dateTime($row['comment_date'], "date") . " at " . dateTime($row['comment_date'], "time") . "</small>
        </h4> 
        <p>". $row['comment_content'] . "</p>
        <p><a class=\"pull-left\" style=\"padding: 2px; font-size: 15px;\" href=\"index.php?source=blog_post&blog_id=" . $post_id . "&reply=" . $row['comment_id'] . "\">Reply</a></p>
        <br>
      ";
    
      $nestedComment = displayNestedComment($row['comment_id']);
      $closeComment = "</div></div>"; 

      

    echo $openComment, $nestedComment, $closeComment;
  }
}

function displayNestedComment($target_id) {
  global $conn;
  $post_id = $_GET["blog_id"];

  try {
    $query = $conn->prepare("SELECT * FROM comments, users WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id = $target_id AND users.user_id = comments.comment_author_id");
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   $nestedComment = 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"#\">
        <img class=\"media-object\" src=\"http://placehold.it/64x64\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['user_username'] . "
          <small>" . dateTime($row['comment_date'], "date") . " at " . dateTime($row['comment_date'], "time") . "</small>
        </h4> 
        <p>". $row['comment_content'] . "</p>
        <p><a class=\"pull-left\" style=\"padding: 2px; font-size: 15px;\" href=\"index.php?source=blog_post&blog_id=" . $post_id . "&reply=" . $row['comment_id'] . "\">Reply</a></p>
      </div>
    ";

      return $nestedComment;
  }
}

function displayPost() {
global $conn;
$post_id = $_GET["blog_id"];

$query = $conn->prepare("SELECT * FROM posts, users WHERE posts.post_id = $post_id AND posts.post_status_id = 4 AND users.user_id = posts.post_author_id");
$query->execute();

while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

  echo "
   <h1>" . $row['post_title'] . "</h1>
    <p class=\"lead\">
        by <a href=\"#\">" . $row['user_username'] . "</a>
    </p>  
    <hr>
   <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . dateTime($row['post_date'], "date") . " at " . dateTime($row['post_date'], "time") . "</p>
    <hr>
    <img class=\"img-responsive\" src=\"includes/img/" . $row['post_image'] . "\" alt=\"\">
   <hr>
    <p class=\"lead\">" . $row['post_content'] . "</p>
    ";
  }
}

function searchPosts() {
  global $conn;

  $search = $_POST["search"];

  $query = $conn->prepare("SELECT * FROM posts WHERE post_tags LIKE '%$search%' AND post_status_id = 4");
  $query->execute();
  
  $count = $query->rowCount();

  if($count == 0) {
    echo "<h1>No Results!</h1>";
  } else {
    showPosts($query);
  }
}


function showPosts($query) {
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "                    
   <h2>
   <a href=\"index.php?source=blog_post&blog_id=" . $row['post_id'] . "\">" . $row['post_title'] . "</a>
  </h2>
  <p class=\"lead\">
   by <a href=\"index.php\">" . $row['user_username'] . "</a>
  </p>
  <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . dateTime($row['post_date'], "date") . " " . dateTime($row['post_date'], "time") . "</p>
  <hr>
  <img class=\"img-responsive\" src=\"includes/img/" . $row['post_image'] . "\" alt=\"\">
  <hr>
  <p>" . $row['post_content'] . "</p>
  <a class=\"btn btn-primary\" href=\"index.php?source=blog_post&blog_id=" . $row['post_id'] . "\">Read More <span class=\"glyphicon glyphicon-chevron-right\"></span></a>

  <hr>
    ";
}
}

function displayNavigation () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM categories");
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

function displayCategoryPosts() {
  global $conn;
  $category_id = $_GET["category"];

  $query = $conn->prepare("SELECT * FROM posts WHERE post_category_id = $category_id AND post_status_id = 4");
  $query->execute();
  
  showPosts($query);
}

function displayPosts () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts, users WHERE post_status_id = 4 AND users.user_id = posts.post_author_id ORDER BY post_date DESC LIMIT 5");
  $query->execute();
  
  showPosts($query);
}

function displayCategories($position) {
  global $conn;

  $stmt = "SELECT * FROM categories $position";
  $query = $conn->prepare($stmt);
  $query->execute();

  showCategory($query);  
}

function showCategory($query) {
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "<li><a href=\"index.php?source=main_page&category=" . $row['cat_id'] . "\">" . $row['cat_title'] . "</a></li>";
}
}

?>
