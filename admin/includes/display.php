<?php 

  if(isset($_GET["source"])) {
    $source = $_GET['source'];
  } else {
    $source = "";
  }

  switch($source) {
    case "categories";
      require("includes/display/categories.php");
      break;
    case "view_all_posts";
      require("includes/display/post/all_posts.php");
      break;
    case "add_post";
      require("includes/display/post/add_post.php");
      break;
    case "edit_post";
      require("includes/display/post/edit_post.php");
      break;
    case "view_all_comments";
      require("includes/display/comment/all_comments.php");
      break;
    case "edit_comments";
      require("includes/display/comment/edit_comment.php");
      break;
    case "add_comments";
      require("includes/display/comment/add_comment.php");
      break;
    case "view_all_users";
      require("includes/display/user/all_users.php");
      break;
    case "add_user";
      require("includes/display/user/add_user.php");
      break;
    case "edit_user";
      require("includes/display/user/edit_user.php");
      break;

  

  }
?>