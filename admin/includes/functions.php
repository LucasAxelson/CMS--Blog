<?php 

function displayImage() {
  global $conn;

  $query = $conn->prepare("SELECT post_image FROM posts");
  $query->execute();

  $image = $query->fetchColumn();

 echo "<img width=\"100px\" src=\"../includes/img/$image\" alt=\"\">";

}

function editPost($id) {
  global $conn;

  $category = $_POST['post_category_id'];
  $title = $_POST['post_title'];
  $author = $_POST['post_author'];
  $content = $_POST['post_content'];
  $tags = $_POST['post_tags'];
  $img_name = $_FILES['post_image']['name'];
  $img_location = $_FILES['post_image']['tmp_name'];

  move_uploaded_file($img_location, "../includes/img/$img_name");

  try {
    $query = $conn->prepare(
      "UPDATE posts 
      SET post_category_id = '$category', post_title = '$title', post_author = '$author', post_image = '$img_name', post_content = '$content', post_tags = '$tags' 
      WHERE post_id = $id"
    );
    $query->execute();
    header("Location:index.php?source=view_all_posts");  
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  }
  
}

function createPost() {
  global $conn;

  $category = $_POST['post_category_id'];
  $title = $_POST['post_title'];
  $author = $_POST['post_author'];
  $content = $_POST['post_content'];
  $tags = $_POST['post_tags'];

  $img_name = $_FILES['post_image']['name'];
  $img_location = $_FILES['post_image']['tmp_name'];

  move_uploaded_file($img_location, "../includes/img/$img_name");

  $query = $conn->prepare("INSERT INTO posts (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags) VALUES ('$category', '$title', '$author', NOW(), '$img_name' , '$content', '$tags')");
  $query->execute();
}

function deletePost() {
  global $conn;
  $id = $_GET['delete'];

  try {
    $query = $conn->prepare("DELETE FROM posts WHERE post_id = $id");
    $query->execute();
    header("Location:index.php?source=view_all_posts");   
  } catch(PDOException $e) {
    echo $e->getMessage();
  }
}

function declarePosts() {
  global $conn;

  $query = $conn->prepare("SELECT * FROM posts, categories WHERE categories.cat_id = posts.post_category_id");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "          
     <tr>
        <td>" . $row['post_id'] . "</td>
        <td>" . $row['post_title'] . "</td>
        <td>" . $row['post_author'] . "</td>
        <td>" . $row['cat_title'] . "</td>
        <td>" . $row['post_status'] . "</td>
        <td><img width=\"100px\" src=\"../includes/img/" . $row['post_image'] . "\" alt=\"" . $row['post_image'] . "\"></td>
        <td>" . $row['post_tags'] . "</td>
        <td><a href='index.php?source=view_all_posts&delete=" . $row['post_id'] . "'>Delete</a></td>
        <td><a href='index.php?source=edit_post&edit=" . $row['post_id'] . "'>Edit</a></td>
     </tr>";
  }
}

function declareCategories () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM categories");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "                            
      <tr>
        <td>" . $row['cat_id'] . "</td>
        <td>" . $row['cat_title'] . "</td>
        <td><a href='index.php?source=categories&delete=" . $row['cat_id'] . "'>Delete</a></td>
        <td><a href='index.php?source=categories&edit=" . $row['cat_id'] . "'>Edit</a></td>
      </tr>
    ";
  }
}

function listCategories () {
  global $conn;

  $query = $conn->prepare("SELECT * FROM categories");
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo " 
    <option value=\"" . $row['cat_id'] . "\">" . $row['cat_title'] . "</option>
    ";
  }
}

function createCategory($category) {
  global $conn;

  $query = $conn->prepare("INSERT INTO categories (cat_title) VALUES ('$category')");
  $query->execute();
}

function editCategory($id, $title) {
  global $conn;

  try {
    $query = $conn->prepare("UPDATE categories SET cat_title = '$title' WHERE cat_id = $id");
    $query->execute();
    header("Location:index.php?source=categories");   
  } catch (PDOException $e) {
    echo "". $e->getMessage() ."";
  }
}

function deleteCategory($id) {
  global $conn;

  try {
    $query = $conn->prepare("DELETE FROM categories WHERE cat_id = $id");
    $query->execute();
    header("Location:index.phpsource=categories");   
  } catch(PDOException) {
    return false;
  }
  return true;
}

function declareError($error) {
  echo "<p>Error: $error</p>";
}
?>