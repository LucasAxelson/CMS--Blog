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
                <!-- Comment -->
                <?php if(isset($_GET['blog_id'])) { displayComments(); } ?>

                        <hr>

                        <?php if(isset($_POST['form_submit'])) { createUserComment(); } ?>
                      
                        <!-- Comments Form -->
                        <div class="well">
                            <h4>Leave a Comment:</h4>
                            <form role="form" method="POST" enctype="multipart/form-data">

                              <div class="row">
                                <label class="col-sm-2 col-form-label" for="authorComment">Author</label>
                                <div class="col-sm-10">
                                  <input type="text" class="form-control" id="authorComment" name="form_author">
                                </div>
                              </div>
                              
                              <div class="row">
                                <label class="col-sm-2 col-form-label" for="emailComment">Email</label>
                                <div class="col-sm-10">
                                  <input type="email" class="form-control" name="form_email" id="emailComment">
                                </div>
                              </div>

                              <div class="row">
                                <label class="col-sm-2 col-form-label" for="contentComment">Content</label>
                                <div class="col-sm-10"> 
                                  <textarea class="form-control" id="contentComment" name="form_content" rows="3"></textarea>
                                </div>
                              </div>

                              <button type="submit" name="form_submit" class="btn btn-primary">Submit</button>

                            </form>
                        </div>
                                
                    </div>
                </div>