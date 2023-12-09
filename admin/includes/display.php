<?php 

  if(isset($_GET["source"])) {
    $source = $_GET['source'];
  } else {
    $source = "";
  }

  switch($source) {
    case "view_all_posts";
      require("includes/display/all_posts.php");
      break;
    case "categories";
      require("includes/display/categories.php");
      break;
    case "add_post";
      require("includes/display/add_post.php");
      break;
    case "edit_post";
      require("includes/display/edit_post.php");
      break;
    case "view_all_posts";
      require("includes/display/all_posts.php");
      break;
    case "view_all_comments";
      require("includes/display/all_comments.php");
      break;
    case "edit_comments";
      require("includes/display/edit_comment.php");
      break;
    case "add_comments";
      require("includes/display/add_comment.php");
      break;
  

  }
?>