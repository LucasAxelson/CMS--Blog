<?php 

function displayCategoryPosts() {
  global $conn;
  $category_id = $_GET["category"];

  $query = $conn->prepare(selectStatement("posts, users", "post_category_id = $category_id AND post_status_id = 4 AND users.user_id = posts.post_author_id"));
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

function displayPosts () {
  global $conn;

  $query = $conn->prepare(selectStatement("posts, users", "posts.post_status_id = 4 AND users.user_id = posts.post_author_id ORDER BY posts.post_created DESC LIMIT 5"));
  $query->execute();
  
  showPosts($query);
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
     <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . dateTime($row['post_created'], "date") . " at " . dateTime($row['post_created'], "time") . "</p>
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
    <p><span class=\"glyphicon glyphicon-time\"></span> Posted on " . dateTime($row['post_created'], "date") . " " . dateTime($row['post_created'], "time") . "</p>
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

function createPost($dir = "") {
  global $conn, $post;

  $post = array();

  if(isset($_POST['post_category_id'])) {
    $post['post_category_id'] = $_POST['post_category_id'];
  }

  if(isset($_POST['post_status_id'])) {
    $post['post_status_id'] = $_POST['post_status_id'];
  } else {
    $post['post_status_id'] = 1;
  }

  if(isset($_POST['post_author'])) {
    $post['post_author_id'] = $_POST['post_author'];
  } else {
    $post['post_author_id'] = $_SESSION['user_id'];
  }

  if(isset($_POST['post_title'])  && verifyText($_POST['post_title'])) {
    $title = trim_input($_POST['post_title']);
    $post['post_title'] = $title;
  }

  if(isset($_POST['post_content']) && verifyText($_POST['post_content'])) {
    $content = $_POST['post_content'];
    $post['post_content'] = $content;
  }
  
  if(isset($_POST['post_tags']) && verifyText($_POST['post_tags'])) {
    $tags = trim_input($_POST['post_tags']);
    $post['post_tags'] = $tags;
  }

  if(isset($_FILES['uploaded_image']['name'])) {
    // Establish path to directory
    $img_dir = $dir . "includes/img/";

    // Include file in path to check for files with the same name
    $img_dir_new = $img_dir . basename($_FILES['uploaded_image']['name']);
    
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
      if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_new)) {
        $post['post_image'] = $_FILES['uploaded_image']['name']; 
      }
    } else if ($uploadOk == 1 && file_exists($img_dir_new))  { 
      if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_rnd)) {
        $post['post_image'] = $file_name;
      }
    }
  }

  $stmt = postStatement("add", $post);
  
  $query = $conn->prepare($stmt);
  $query->execute();
}

function postStatement($statement, $post_array, $id = NULL) {
  if ($statement == "edit") {

    $stmt =  "UPDATE posts
      SET post_modified = NOW()";
    $where = " WHERE post_id = $id";

    foreach( $post_array as $key => $content ) {
      $stmt.= ", $key = '$content'";
    }

    return $stmt . $where; 

  } else if ($statement == "add") {

    $insert =  "INSERT INTO posts (";
    $insert_close = "post_created)";
    $value = " VALUES (";
    $value_close = "NOW())";

    foreach( $post_array as $key => $content ) {
      $insert .= "$key, ";
      $value .= "'$content', ";
    }

    return $insert . $insert_close . $value . $value_close;

  }
}

function editPost($dir, $id) {
  global $conn, $post;
  
    $post = array();
  
    if(isset($_POST['post_category_id']) && $_POST['post_category_id'] != "blank") {
      $post['post_category_id'] = $_POST['post_category_id'];
    }
  
    if(isset($_POST['post_status_id']) && $_POST['post_status_id'] != "blank") {
      $post['post_status_id'] = $_POST['post_status_id'];
    }
  
    if(isset($_POST['post_author']) && $_POST['post_author'] != "blank") {
      $post['post_author_id'] = $_POST['post_author'];
    } else if( $_POST['post_author'] == "blank") {
      
    } else {
      $post['post_author_id'] = $_SESSION['user_id'];
    }
  
    if(isset($_POST['post_title'])  && verifyText($_POST['post_title'])) {
      $title = trim_input($_POST['post_title']);
      $post['post_title'] = $title;
    }
  
    if(isset($_POST['post_content']) && verifyText($_POST['post_content'])) {
      $content = $_POST['post_content'];
      $post['post_content'] = $content;
    }
    
    if(isset($_POST['post_tags']) && verifyText($_POST['post_tags'])) {
      $tags = trim_input($_POST['post_tags']);
      $post['post_tags'] = $tags;
    }
  
    if(!empty($_FILES['uploaded_image']['tmp_name'])) {
      // Establish path to directory
      $img_dir = $dir . "includes/img/";
  
      // Include file in path to check for files with the same name
      $img_dir_new = $img_dir . basename($_FILES['uploaded_image']['name']);
      
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
        if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_new)) {
          $post['post_image'] = $_FILES['uploaded_image']['name']; 
        }
      } else if ($uploadOk == 1 && file_exists($img_dir_new))  { 
        if(move_uploaded_file($_FILES['uploaded_image']['tmp_name'], $img_dir_rnd)) {
          $post['post_image'] = $file_name;
        }
      }
    }   
  
    $stmt = postStatement("edit", $post, $id);
  
  try {
    $query = $conn->prepare($stmt);
    $query->execute();
    header("Location:index.php?source=view_all_posts");  
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
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
        <td><input class=\"td-check\" type=\"checkbox\" name=\"checkboxArray[]\" value=\"" . $row['post_id'] . "\"></td>
        <td class=\"td-style\">" . $row['post_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
        <td class=\"td-style\">" . $row['user_username'] . "</td>
        <td class=\"td-style\">" . dateTime($row['post_created'], "date") . "</td>
        <td class=\"td-style\">" . $row['cat_title'] . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-image-div\"><img class=\"td-image\" src=\"../includes/img/" . $row['post_image'] . "\" alt=\"" . $row['post_image'] . "\"></td>
        <td class=\"td-style\">" . $row['post_tags'] . "</td>
        <td class=\"td-style\">" . $row['post_comment_count'] . "</td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-view\" href='../index.php?source=blog_post&blog_id=" . $row['post_id'] . "'>View</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_posts&approve=" . $row['post_id'] . "'>Approve</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_posts&reject=" . $row['post_id'] . "'>Reject</a></td>
        <td class=\"td-div\"><a onClick=\" javascript: return confirm('Are you sure that you wish to delete this item?')\" class=\"td-btn td-btn-danger\" href='index.php?source=view_all_posts&delete=" . $row['post_id'] . "'>Delete</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_post&edit=" . $row['post_id'] . "'>Edit</a></td>
     </tr>";
  }
}

?>