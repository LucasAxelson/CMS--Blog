<?php

  if(isset($_GET["source"])) {
    $source = $_GET['source'];
  } else {
    $source = "";
  }

  switch($source) {
    case "blog_post";
      require("includes/display/post.php");
      break;
    case "main_page";
      require("includes/display/main.php");
      break;
    case "login_page";
      require("includes/display/log_in.php");
      break;
    case "create_account";
      require("includes/display/create_account.php");
      break;
    case "create_post";
      require("includes/display/create_post.php");
      break;
    case "profile_page";
      require("includes/display/profile_user.php");
      break;
    default:
      require("includes/display/main.php"); 
  }
?>