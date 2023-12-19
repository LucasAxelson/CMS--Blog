<?php

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

function createUserPost() {
  global $conn;

  if(verifyText($_POST['post_content']) && verifyText($_POST['post_title']) && verifyTags($_POST['post_tags'])) {
    $postData = tempArray();
      
    $postData['author'] = $_SESSION['user_id'];
    $postData['category'] = $_POST['post_category_id'];
    $postData['title'] = trim_input($_POST['post_title']);
    $postData['content'] = $_POST['post_content'];
    $postData['tags'] = trim_input($_POST['post_tags']);    
    
    $img_dir = "includes/img/";
    $img_dir .= basename($_FILES['account_image']['name']);
  
    $uploadOk = prepareImage($img_dir);

    if($uploadOk == 1) {
      if(move_uploaded_file($_FILES['account_image']['tmp_name'], $img_dir )) {
  
      $stmt = postStatement("add", $postData['category'], $postData['title'], $postData['author'], $postData['content'], $postData['tags'], $_FILES['account_image']['name'], "yes", $_SESSION['user_id']);
  
    }} else {
      $stmt = postStatement("add", $postData['category'], $postData['title'], $postData['author'], $postData['content'], $postData['tags'], $_FILES['account_image']['name'], "no", $_SESSION['user_id']);
    }

    $query = $conn->prepare($stmt);
    $query->execute();
  }
}

function editUserPost($id) {
  global $conn;

  if(verifyText($_POST['edit_content']) && verifyText($_POST['edit_title']) && verifyTags($_POST['edit_tags'])) {
    $postData = tempArray();
      
    $postData['author'] = $_SESSION['user_id'];
    $postData['category'] = $_POST['edit_category_id'];
    $postData['title'] = trim_input($_POST['edit_title']);
    $postData['content'] = $_POST['edit_content'];
    $postData['tags'] = trim_input($_POST['edit_tags']);    
    
    $img_name = $_FILES['edit_image']['name'];
    $img_location = $_FILES['edit_image']['tmp_name'];

    if(!empty($img_name) && !empty($img_location)) {
      move_uploaded_file($img_location, "includes/img/$img_name");
  
      $stmt = postStatement("edit", $postData['category'], $postData['title'], $postData['author'], $postData['content'], $postData['tags'], $img_name, "yes", $id);
  
    } else {
      $stmt = postStatement("edit", $postData['category'], $postData['title'], $postData['author'], $postData['content'], $postData['tags'], $img_name, "no", $id);
    }

    $query = $conn->prepare($stmt);
    $query->execute();
  }
}


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
        <img class=\"media-object comment-image\" src=\"includes/img/" . $row['user_image'] . "\" alt=\"\">
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

function displayPost() {
global $conn;
$post_id = $_GET["blog_id"];

$query = $conn->prepare(selectStatement("posts, users", "posts.post_id = $post_id AND posts.post_status_id = 4 AND users.user_id = posts.post_author_id"));
$query->execute();

while( $row = $query->fetch(PDO::FETCH_ASSOC) ) {

  echo "
   <h1>" . $row['post_title'] . "</h1>
    <p class=\"lead\">
        by <a href=\"index.php?source=profile_page&page=" . $row['user_id'] . "\">" . $row['user_username'] . "</a>
    </p>  
    <hr>
   <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . dateTime($row['post_date'], "date") . " at " . dateTime($row['post_date'], "time") . "</p>
    <hr>
    <div style=\"display: flex; justify-content: center;\">
    <img class=\"img-responsive\" style=\"width: 480px; height: 270px;\" src=\"includes/img/" . $row['post_image'] . "\" alt=\"\">
    </div>
    <hr>
    <p class=\"lead\">" . $row['post_content'] . "</p>
    ";
  }
}

function searchPosts() {
  global $conn;

  $search = $_POST["search"];

  $query = $conn->prepare("SELECT * FROM posts WHERE post_status_id = 4 AND post_title LIKE '%" . $search . "%' OR post_tags LIKE '%" . $search . "%'");
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
  <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . dateTime($row['post_date'], "date") . " " . dateTime($row['post_date'], "time") . "</p>
  <hr>
  <div style=\"display: flex; justify-content: center;\">
  <img class=\"img-responsive\" style=\"width: 320px; height: 180px;\" src=\"includes/img/" . $row['post_image'] . "\" alt=\"\">
  </div>
  <hr><p>" 
  . $row['post_content'] . "</p>
  <a class=\"btn btn-primary\" href=\"index.php?source=blog_post&blog_id=" . $row['post_id'] . "\">Read More <span class=\"glyphicon glyphicon-chevron-right\"></span></a>

  <hr>
    ";
}
}
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

function displayCategoryPosts() {
  global $conn;
  $category_id = $_GET["category"];

  $query = $conn->prepare(selectStatement("posts, users", "post_category_id = $category_id AND post_status_id = 4 AND users.user_id = posts.post_author_id"));
  $query->execute();
  
  showPosts($query);
}

function displayPosts () {
  global $conn;

  $query = $conn->prepare(selectStatement("posts, users", "posts.post_status_id = 4 AND users.user_id = posts.post_author_id ORDER BY posts.post_date DESC LIMIT 5"));
  $query->execute();
  
  showPosts($query);
}

function displayCategories($position) {
  global $conn;

  $stmt = selectStatement("categories $position", "");
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
