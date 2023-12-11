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
                <!-- <div class="well"> -->
                  <!-- <h4>Leave a Comment:</h4> -->
                  <!-- <form role="form"> -->
                    <!-- <div class="form-group"> -->
                      <!-- <textarea class="form-control" rows="3"></textarea> -->
                    <!-- </div> -->
                    <!-- <button type="submit" class="btn btn-primary">Submit</button> -->
                  <!-- </form> -->
                <!-- </div> -->

                <hr>
  
                <!-- Posted Comments -->
                <?php if(isset($_GET['blog_id'])) { displayComments(); } ?>

                        <hr>

                <?php require("includes/display/create_comment.php") ?>
                                
                    