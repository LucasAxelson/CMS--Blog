<?php
function displayPost() {
global $conn;
$post_id = $_GET["blog_id"];

$query = $conn->prepare("SELECT * FROM posts WHERE post_id = $post_id");
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
   <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . $date . " at " . substr($items[1], 0, strlen($items[1]) - 3) . "</p>
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

  $query = $conn->prepare("SELECT * FROM posts WHERE post_tags LIKE '%$search%' ");
  $query->execute();
  
  $count = $query->rowCount();

  if($count == 0) {
    echo "<h1>No Results!</h1>";
  } else {
    showPosts($query);
  }
}

function showCategory($query) {
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "<li><a href=\"#\">" . $row['cat_title'] . "</a></li>";
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
   <a href=\"blog_post.php?blog_id=" . $row['post_id'] . "\">" . $row['post_title'] . "</a>
  </h2>
  <p class=\"lead\">
   by <a href=\"index.php\">" . $row['post_author'] . "</a>
  </p>
  <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . $date . " " . substr($items[1], 0, strlen($items[1]) - 3) . "</p>
  <hr>
  <img class=\"img-responsive\" src=\"includes/img/" . $row['post_image'] . "\" alt=\"\">
  <hr>
  <p>" . $row['post_content'] . "</p>
  <a class=\"btn btn-primary\" href=\"blog_post.php?blog_id=" . $row['post_id'] . "\">Read More <span class=\"glyphicon glyphicon-chevron-right\"></span></a>

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
      <a href=\"index.php?category=" . $row['cat_id'] . "\">" . $row['cat_title'] . "</a>
    </li>
    ";
  }
}

function displayCategoryPosts() {
  global $conn;
  $category_id = $_GET["category"];

  $query = $conn->prepare("SELECT * FROM posts WHERE post_category_id = $category_id");
  $query->execute();
  
  showPosts($query);
}

function displayPosts () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts ORDER BY post_date DESC LIMIT 5");
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


?>
