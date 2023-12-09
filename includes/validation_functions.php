<?php 

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