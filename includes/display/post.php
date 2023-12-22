<?php
// Search function
?>
<!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Post Content Column -->
            <div class="col-lg-8">

                <!-- Blog Post -->

                <?php displayPost() ?>

                <hr>

                <!-- Blog Comments -->

                <!-- Comments Form -->

                <?php if(isset($_SESSION['user_access_id'])) { require("includes/display/user_create_comment.php"); } ?>

                <hr>
  
                <!-- Posted Comments -->
                <?php if(isset($_GET['page'])) { displayComments(); } ?>

                        <hr>

                <?php if(!isset($_SESSION['user_access_id'])) { require("includes/display/create_comment.php"); } ?>
                                
                </div>
                    