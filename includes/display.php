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
   default:
      require("includes/display/main.php"); 
  }
?>