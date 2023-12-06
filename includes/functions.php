<?php

function searchExecute() {
  global $conn;

  $search = $_POST["search"];

  $query = $conn->prepare("SELECT * FROM posts WHERE post_tags LIKE '%$search%' ");
  $query->execute();
  
  $count = $query->rowCount();

  if($count == 0) {
    echo "<h1>No Results!</h1>";
  } else {
    showSearch($query);
  }
}

function showCategory($query) {
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "<li><a href=\"#\">" . $row['cat_title'] . "</a></li>";
}
}

function showSearch($query) {
  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    $items = explode(" ", $row['post_date']);
    $itemsDate = explode("-", $items[0]);
    $date = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

    echo "                    
   <h2>
   <a href=\"#\">" . $row['post_title'] . "</a>
  </h2>
  <p class=\"lead\">
   by <a href=\"index.php\">" . $row['post_author'] . "</a>
  </p>
  <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . $date . " " . substr($items[1], 0, strlen($items[1]) - 3) . "</p>
  <hr>
  <img class=\"img-responsive\" src=\"includes/img/" . $row['post_image'] . "\" alt=\"\">
  <hr>
  <p>" . $row['post_content'] . "</p>
  <a class=\"btn btn-primary\" href=\"#\">Read More <span class=\"glyphicon glyphicon-chevron-right\"></span></a>

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
      <a href=\"#\">" . $row['cat_title'] . "</a>
    </li>
    ";
  }
}

function displayPosts () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts ORDER BY post_date DESC LIMIT 3");
  $query->execute();
  
  showSearch($query);
}

function displayCategories($position) {
  global $conn;

  $stmt = "SELECT * FROM categories $position";
  $query = $conn->prepare($stmt);
  $query->execute();

  showCategory($query);  
}


?>
