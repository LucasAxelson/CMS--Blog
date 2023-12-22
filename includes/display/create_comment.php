<?php if(isset($_POST['visitor_submit'])) { createComment(); } ?>
                      
                      <!-- Comments Form -->
                      <div class="well">
                          <h4>Leave a Comment:</h4>
                          <form action="<?php $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">

                            <div class="row">
                              <label class="col-sm-2 col-form-label" for="authorComment">Author</label>
                              <div class="col-sm-10">
                                <input type="text" class="form-control" name="visitor_username" id="authorComment">
                              </div>
                            </div>
                            
                            <div class="row">
                              <label class="col-sm-2 col-form-label" for="emailComment">Email</label>
                              <div class="col-sm-10">
                                <input type="email" class="form-control" name="visitor_email" id="emailComment">
                              </div>
                            </div>

                            <div class="row">
                              <label class="col-sm-2 col-form-label" for="contentComment">Content</label>
                              <div class="col-sm-10"> 
                                <textarea class="form-control" id="contentComment" name="comment_content" rows="3"></textarea>
                              </div>
                            </div>

                            <button type="submit" name="visitor_submit" class="btn btn-primary">Submit</button>

                          </form>
                      </div>