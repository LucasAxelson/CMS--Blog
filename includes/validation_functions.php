<?php 

function selectStatement($tables, $where) {
  if (trim($where) == "") {
    return "SELECT * FROM " . $tables . "";
  } else {
    return "SELECT * FROM " . $tables . " WHERE " . $where;
  }
}

function createPostStatement ($category, $title, $img_name, $author, $content, $tags, $img = "yes") {
  if ($img == "yes") {
  return "INSERT INTO posts (post_category_id, post_title, post_date, post_image, post_status_id, post_author_id, post_content, post_tags) VALUES ('$category', '$title', NOW(), '$img_name', 1 , '$author', '$content', '$tags')";
  } else if ($img == "no") {
    return "INSERT INTO posts (post_category_id, post_title, post_date, post_status_id, post_author_id, post_content, post_tags) VALUES ('$category', '$title', NOW(), 1 , '$author', '$content', '$tags')";
  }
}

// function commentStatement($statement, $username, $legal_name, $email, $status, $img_name, $id = "", $optional = "yes") {};
// function postStatement($statement, $username, $legal_name, $email, $status, $img_name, $id = "", $optional = "yes") {};


function userStatement($statement, $username, $legal_name, $email, $status, $img_name, $id = "", $optional = "yes") {
  if ($statement == "edit") {

    if ($optional == "yes") {
      return "UPDATE users
      SET user_username = '$username', user_legal_name = '$legal_name', user_email = '$email', user_status_id = '$status', user_image = '$img_name', user_modified = NOW() 
      WHERE user_id = $id";
    } else if ($optional == "no") {
      return "UPDATE users
      SET user_username = '$username', user_legal_name = '$legal_name', user_email = '$email', user_status_id = '$status', user_modified = NOW() 
      WHERE user_id = $id";
    }

  } else if ($statement == "add") {
    
    if ($optional == "yes") {
        return "INSERT INTO users (user_username, user_legal_name, user_email, user_status_id, user_image, user_created) VALUES ('$username', '$legal_name', '$email', '$status', '$img_name',  NOW())";
      } else if($optional = "no") {
        return "INSERT INTO users (user_username, user_legal_name, user_email, user_status_id, user_created) VALUES ('$username', '$legal_name', '$email', '$status', NOW())";
      }

  }
}

function dateTime($data, $choice) {
  $items = explode(" ", $data);
  $itemsDate = explode("-", $items[0]);
  $created = "$itemsDate[2]/$itemsDate[1]/$itemsDate[0]"; 

  if($choice == "time") {
    $time = setTime($items);   
    return $time;
  } else if ($choice == "date") {
    return $created;
  }
}

function setTime($time) {
  $time = substr($time[1], 0, strlen($time[1]) - 3);   
  return $time;
}

function trim_input($input) {
  //Remove whitespaces, quote-safing and string escaping of inputted data
  $input = trim($input);
  $input = stripslashes($input);
  $input = htmlspecialchars($input, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
  return $input;
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