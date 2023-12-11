<?php

function createUserComment() {
  global $conn;

  $post_id = $_GET['blog_id'];

  if(verifyText($_POST['form_content']) && verifyText($_POST['form_author'])) {
    $author = trim_input($_POST['form_author']);
    $content = trim_input($_POST['form_content']);
    $email = trim_input($_POST['form_email']);

    $stmt = "INSERT INTO comments (comment_post_id, comment_email, comment_content, comment_author, comment_status_id, comment_date) VALUES ('$post_id', '$email', '$content', '$author', '1' , NOW())";

    if(isset($_GET['reply'])) { 
      $reply_id = $_GET['reply']; 
      $stmt = "INSERT INTO comments (comment_post_id, comment_reply_id, comment_email, comment_content, comment_author, comment_status_id, comment_date) VALUES ('$post_id', '$reply_id', '$email', '$content', '$author', '1' , NOW())";    
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
    $query = $conn->prepare("SELECT * FROM comments WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id IS NULL");
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   $items = explode(" ", $row['comment_date']);
   $itemsDate = explode("-", $items[0]);
   $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

   $openComment = 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"#\">
        <img class=\"media-object\" src=\"http://placehold.it/64x64\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['comment_author'] . "
          <small>" . $date . " at " . setTime($items) . "</small>
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
    $query = $conn->prepare("SELECT * FROM comments WHERE comment_post_id = $post_id AND comment_status_id = 4 AND comment_reply_id = $target_id");
    $query->execute();
  } catch (PDOException $e) {
    echo $e->getMessage();
  }

  while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

   $items = explode(" ", $row['comment_date']);
   $itemsDate = explode("-", $items[0]);
   $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

   $nestedComment = 
   "<div class=\"media\">
      <a class=\"pull-left\" href=\"#\">
        <img class=\"media-object\" src=\"http://placehold.it/64x64\" alt=\"\">
      </a>
      <div class=\"media-body\">
        <h4 class=\"media-heading\">" . $row['comment_author'] . "
          <small>" . $date . " at " . setTime($items) . "</small>
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

$query = $conn->prepare("SELECT * FROM posts WHERE post_id = $post_id AND post_status_id = 4");
$query->execute();

while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

  $items = explode(" ", $row['post_date']);
  $itemsDate = explode("-", $items[0]);
  $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

  echo "
   <h1>" . $row['post_title'] . "</h1>
    <p class=\"lead\">
        by <a href=\"#\">" . $row['post_author'] . "</a>
    </p>  
    <hr>
   <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . $date . " at " . setTime($items) . "</p>
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

    $items = explode(" ", $row['post_date']);
    $itemsDate = explode("-", $items[0]);
    $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

    echo "                    
   <h2>
   <a href=\"index.php?source=blog_post&blog_id=" . $row['post_id'] . "\">" . $row['post_title'] . "</a>
  </h2>
  <p class=\"lead\">
   by <a href=\"index.php\">" . $row['post_author'] . "</a>
  </p>
  <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . $date . " " . setTime($items) . "</p>
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

  $query = $conn->prepare("SELECT * FROM posts WHERE post_status_id = 4 ORDER BY post_date DESC LIMIT 5");
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
