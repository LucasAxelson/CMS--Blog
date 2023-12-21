<?php 

function applyOption() {
  global $conn;
  $option = $_POST['bulkOptions'];

  foreach ($_POST['checkboxArray'] as $checkboxValue) {
      // Draft All
    if($option == "draft_all") {
      $stmt = "UPDATE posts 
               SET post_status_id = '1' 
               WHERE post_id = {$checkboxValue}";
    }
    // Approve All
    if($option == "approve_all") {
      $stmt = "UPDATE posts 
               SET post_status_id = '4' 
               WHERE post_id = {$checkboxValue}";
    }
    // Reject All
    if($option == "reject_all") {
      $stmt = "UPDATE posts 
               SET post_status_id = '3' 
               WHERE post_id = {$checkboxValue}";
    }
    // Delete All
    if($option == "delete_all") {
      $stmt = "DELETE FROM posts WHERE post_id = {$checkboxValue}";
    }

    $query = $conn->prepare($stmt);
    $query->execute();
  }
}

function generateRandomString($length = 10) {
  $characters = 'abcdefghijklmnopqrstuvwxyz';
  $charactersLength = strlen($characters);

  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[random_int(0, $charactersLength - 1)];
  }
  return $randomString;
}

function tempArray() {
  $array = array (
    "id"=> NULL,
    "title"=> NULL,
    "email"=> NULL,
    "author"=> NULL,
    "username"=> NULL,
    "legal_name"=> NULL,
    "image"=> NULL,
    "content"=> NULL,
    "tags"=> NULL,
    "date"=> NULL,
    "reply_id"=> NULL,
    "status_id"=> NULL,
    "access_id"=> NULL,
  );
return $array;
}

function countTotal($item, $where = "") {
  global $conn;

  $query = $conn->prepare(selectStatement($item, $where));
  $query->execute();
  $num = $query->rowCount();

  return $num;
}

function prepareImage($img_dir) {

  $imageFileType = strtolower(pathinfo($img_dir, PATHINFO_EXTENSION));
  $check = getimagesize($_FILES["uploaded_image"]["tmp_name"]);

  if(empty($_FILES['uploaded_image']['name'])) {
    // Check if file is empty 
    return 0;

  } else if($check == false) {
    // Check if image file is a actual image or fake image
    echo "File is not an image.";
    return 0;

  } else if ($_FILES["uploaded_image"]["size"] > 50000000) {
    // Check if file is larger than 50mb
    echo "Sorry, your file is too large.";
    return 0;

  } else if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" &&  $imageFileType != "jfif") {
    // Allow only certain file formats
    echo "Sorry, only JPG, JPEG, JFIF & PNG files are allowed.";
    return 0;

  } else {
    // If all checks are passed, submit file
    return 1;
  }
}




function showAdmin() {                      
    echo "
        <li>
            <a href=\"admin/index.php?source=dashboard\">Admin</a>
        </li>
        ";
  
}

function listItems ($item, $placeholder) {
  global $conn;
  $stmt = selectStatement($item, $placeholder); 
   

  $query = $conn->prepare($stmt);
  $query->execute();

  while( $row = $query->fetch(PDO::FETCH_ASSOC ) ) {
    extract( $row );

    if ($item == "users") {
      echo "<option value=\"" . $row['user_id'] . "\">" . $row['user_username'] . "</option>";
    }
     else if ($item == "posts") {
      echo "<option value=\"" . $row['post_id'] . "\">" . $row['post_title'] . "</option>";
    }
     else if ($item == "categories") {
      echo "<option value=\"" . $row['cat_id'] . "\">" . $row['cat_title'] . "</option>";
    }
     else if ($item == "status") {
      echo "<option value=\"" . $row['status_id'] . "\">" . $row['status_name'] . "</option>";
    }
     else if ($item == "access") {
      echo "<option value=\"" . $row['access_id'] . "\">" . $row['access_title'] . "</option>";
    }
    else if ($item == "comments") {
      echo "<option value=\"" . $row['comment_id'] . "\">" . $row['comment_author'] . "</option>";
    }
  }
}

function ApproveRejectOrDelete($item, $decision) {
  global $conn;

  if ($decision == "approve") {
    $id = $_GET['approve'];

    if ($item == "user") {
      $query = $conn->prepare("UPDATE users SET user_status_id = '4' WHERE user_id = $id");
      $query->execute();
    }
    else if ($item == "post") {
      $query = $conn->prepare("UPDATE posts SET post_status_id = '4' WHERE post_id = $id");
      $query->execute();
    }
    else if ($item == "comment") {
      $query = $conn->prepare("UPDATE comments SET comment_status_id = '4' WHERE comment_id = $id");
      $query->execute();
    }
    
  } else if ($decision == "reject") {
    $id = $_GET['reject'];

    if ($item == "user") {
      $query = $conn->prepare("UPDATE users SET user_status_id = '3' WHERE user_id = $id");
      $query->execute();
    }
    else if ($item == "post") {
      $query = $conn->prepare("UPDATE posts SET post_status_id = '3' WHERE post_id = $id");
      $query->execute();
    }
    else if ($item == "comment") {
      $query = $conn->prepare("UPDATE comments SET comment_status_id = '3' WHERE comment_id = $id");
      $query->execute();
    }
    
  } else if ($decision == "delete") {
    $id = $_GET['delete'];

    if ($item == "user") {
      try {
        $query = $conn->prepare("DELETE FROM users WHERE user_id = $id");
        $query->execute();
        header("Location:index.php?source=view_all_users");   
      } catch(PDOException $e) {
        echo $e->getMessage();
      }
    }
    if ($item == "post") {
      try {
        $query = $conn->prepare("DELETE FROM posts WHERE post_id = $id");
        $query->execute();
        header("Location:index.php?source=view_all_posts");   
      } catch(PDOException $e) {
        echo $e->getMessage();
      }
    }
    if ($item == "comment") {
      try {
        $query = $conn->prepare("DELETE FROM comments WHERE comment_id = $id");
        $query->execute();
        header("Location:index.php?source=view_all_comments");   
      } catch(PDOException $e) {
        echo $e->getMessage();
      }
    }
  }
}

function selectStatement($tables, $where) {
  if (trim($where) == "") {
    return "SELECT * FROM " . $tables . "";
  } else {
    return "SELECT * FROM " . $tables . " WHERE " . $where;
  }
}

function commentStatement($statement, $post_id, $reply_id, $author, $content, $optional = "no", $comment_id = 0) {
  if ($statement == "edit") {

    if ($optional == "no") {
      
      return "UPDATE comments SET comment_post_id = '$post_id', comment_content = '$content', comment_author = '$author' WHERE comment_id = $comment_id";

    } else if ($optional == "yes") {
      
      return "UPDATE comments SET comment_post_id = '$post_id', comment_reply_id = '$reply_id', comment_content = '$content', comment_author = '$author' WHERE comment_id = $comment_id";

    }

  } else if ($statement == "add") {
    
    if ($optional == "no") {

      return "INSERT INTO comments (comment_post_id, comment_author_id, comment_content, comment_status_id, comment_date) VALUES ('$post_id', '$author', '$content', '1' , NOW())";

    } else if($optional = "yes") {
      
      return "INSERT INTO comments (comment_post_id, comment_reply_id, comment_author_id, comment_content, comment_status_id, comment_date) VALUES ('$post_id', '$reply_id', '$author', '$content', '1' , NOW())";

    }

  }

};

function dateTime($data, $choice) {
  $items = explode(" ", $data);
  $itemsDate = explode("-", $items[0]);
  $created = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

  if($choice == "time") {
    $time = substr($items[1], 0, strlen($items[1]) - 3);   
    return $time;
  } else if ($choice == "date") {
    return $created;
  }
}

function trim_input($input) {
  //Remove whitespaces, quote-safing and string escaping of inputted data
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  return $input;
}

// Cleans email before input
function prepareEmail($email) {
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  $email = trim_input($email);
  $email = strtolower($email);
  return $email;
}

// Boolean function designed to check password strength
function checkPasswordStrength ($password) {
  //Define password strength requirements using regular expressions.
   $uppercase = preg_match('@[A-Z]@', $password);
   $lowercase = preg_match('@[a-z]@', $password);
   $number = preg_match('@[0-9]@', $password);
   $specialChar = preg_match('@[^\w]@', $password);
   
   // Define minimum length for the password
   $minLength = 8;
   
   if(!empty($password)) {
     // Check if the password meets all the requirements
    if ($uppercase && $lowercase && $number && $specialChar && strlen($password) >= $minLength) {
      return true;
    } else {
      return false;
    }
  } else {
    return false;
  }
}


// Boolean function designed to validate email prior to input.
function verifyEmail($email) {
  $email = filter_var($email, FILTER_SANITIZE_EMAIL);
  if(empty(trim($email))) {
    return false;
  } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    return false;
  } else {
    return true;
  }
}

// Boolean function designed to verify username, content & title
function verifyText($text) {
  if(empty(trim($text))) {
    return false;
  }
  //  else if (!preg_match("/^[0-9a-zA-Z-,.' ]*$/", $text)) { 
    //Only permit numbers, a-Z characters, hyphens, single quotes and white spaces
    // return false;
  // } 
  else {
    return true;
  }
}

// Boolean function designed to verify tags
function verifyTags($tags) {
  if(empty(trim($tags))) {
    return false;
  } 
  // else if (!preg_match("/^[a-zA-Z-, ]*$/", $tags)) { 
    //Only permit a-Z characters, hyphens and white spaces
    // return false;
  // } 
  else {
    return true;
  }
}

?>