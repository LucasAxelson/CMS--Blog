<?php 

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
        <td><a href='categories.php?delete=" . $row['cat_id'] . "'>Delete</a></td>
      </tr>
    ";
  }
}

function createCategory($category) {
  global $conn;

  $query = $conn->prepare("INSERT INTO categories (cat_title) VALUES ('$category')");
  $query->execute();
}

function deleteCategory($category) {
  global $conn;

  try {
    $query = $conn->prepare("DELETE FROM categories WHERE cat_id = $category");
    $query->execute();   
  } catch(PDOException) {
    return false;
  }
  return true;
}

function declareError($error) {
  echo "<p>Error: $error</p>";
}
?>