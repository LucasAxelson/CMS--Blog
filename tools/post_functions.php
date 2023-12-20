<?php 

function postStatement($statement, $post_array, $id = NULL) {
  if ($statement == "edit") {
    global $stmt;

    $stmt =  "UPDATE posts
      SET post_modified = NOW()";
    $where = " WHERE post_id = $id";

    foreach( $post_array as $key => $content ) {
      $stmt.= ", $key = '$content'";
    }

    return $stmt . $where; 

  } else if ($statement == "add") {
    global $insert, $value;

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
      $content = trim_input($_POST['post_content']);
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
        <td class=\"td-style\">" . $row['post_id'] . "</td>
        <td class=\"td-style td-title\">" . $row['post_title'] . "</td>
        <td class=\"td-style\">" . $row['user_username'] . "</td>
        <td class=\"td-style\">" . dateTime($row['post_created'], "date") . "</td>
        <td class=\"td-style\">" . $row['cat_title'] . "</td>
        <td class=\"td-style\">" . $row['status_name'] . "</td>
        <td class=\"td-image-div\"><img class=\"td-image\" src=\"../includes/img/" . $row['post_image'] . "\" alt=\"" . $row['post_image'] . "\"></td>
        <td class=\"td-style\">" . $row['post_tags'] . "</td>
        <td class=\"td-style\">" . $row['post_comment_count'] . "</td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-success\" href='index.php?source=view_all_posts&approve=" . $row['post_id'] . "'>Approve</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-reject\" href='index.php?source=view_all_posts&reject=" . $row['post_id'] . "'>Reject</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=view_all_posts&delete=" . $row['post_id'] . "'>Delete</a></td>
        <td class=\"td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=edit_post&edit=" . $row['post_id'] . "'>Edit</a></td>
     </tr>";
  }
}

?>