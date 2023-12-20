<?php 

function pullItem($item, $where = "") {
  global $conn;

  $query = $conn->prepare(selectStatement($item, $where));
  $query->execute();

  while ( $row = $query->fetch(PDO::FETCH_ASSOC ) ) { 
    extract($row);

    $array = tempArray();

    if(str_contains($item, "users")) {
      $array["username"] = $row["user_username"];
      $array["legal_name"] = $row["user_legal_name"];
      $array["email"] = $row["user_email"];
      $array["image"] = $row["user_image"];

    } else if (str_contains($item, "comments")) {
      $array["email"] = $row["comment_email"];
      $array["content"] = $row["comment_content"];

    } else if (str_contains($item, "posts")) {
      $array["title"] = $row["post_title"];
      $array["content"] = $row["post_content"];
      $array["tags"] = $row["post_tags"];
    }

    return $array;
  }
}

function seeSelectedItem($item) {
  $id = $_POST['selected_id'];
  $stmt = "Location: index.php?source";
  if($item == "posts") { $stmt .= "=edit_post&edit=$id";}
  if($item == "users") { $stmt .= "=edit_user&edit=$id";}
  if($item == "comments") { $stmt .= "=edit_comments&edit=$id";}
  
  return header($stmt);
}



function displayImage($table, $image_column, $where_column) {
  global $conn;
  
  $id = $_GET['edit'];
  $query = $conn->prepare("SELECT $image_column FROM $table WHERE $where_column = $id");
  $query->execute();
  
  $image = $query->fetchColumn();
  
  echo "<img width=\"100px\" src=\"../includes/img/$image\" alt=\"\">";  
}

function declareCategories () {
  global $conn;

  $query = $conn->prepare(selectStatement("categories", ""));
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    echo "                            
      <tr>
        <td class=\"td-cat\">" . $row['cat_id'] . "</td>
        <td class=\"td-cat\">" . $row['cat_title'] . "</td>
        <td class=\"td-cat td-div\"><a class=\"td-btn td-btn-danger\" href='index.php?source=categories&delete=" . $row['cat_id'] . "'>Delete</a></td>
        <td class=\"td-cat td-div\"><a class=\"td-btn td-btn-info\" href='index.php?source=categories&edit=" . $row['cat_id'] . "'>Edit</a></td>
      </tr>
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