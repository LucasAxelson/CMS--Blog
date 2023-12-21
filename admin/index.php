<?php require("includes/admin_header.php");?>
      
        <?php if($_GET['source'] == "dashboard" OR
                 $_GET['source'] == "categories" OR
                 $_GET['source'] == "view_all_posts" OR 
                 $_GET['source'] == "view_all_comments" OR 
                 $_GET['source'] == "view_all_users") {
                        require("includes/admin_head.php");
                } ?>

                <?php require("includes/display.php"); ?>

<?php require("includes/admin_footer.php"); ?>