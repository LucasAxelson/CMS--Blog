<?php
  if(isset($_POST['edit_post'])) {
    editPost("", $_GET['edit']);
  }

  if(isset($_GET['edit'])) {  
    $post = pullItem("posts, status", "status.status_id = posts.post_status_id AND posts.post_id = " . $_GET['edit']);
  };
?>

<!-- Page Content -->
  <div class="col-lg-8">
  
    <div style="display:flex; flex-direction:column; align-items: center;">

      <h1 style="margin: 3rem;" class="text-center">Create a Post</h1>
      
      <div style="width:75%;">

      <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label class="form-label" for="title">Post Title</label>
        <input type="text" id="title" name="post_title" class="form-control" value="<?php if(isset($_GET['edit'])) { echo $post['title']; } ?>">
      </div>

      <div class="form-group">
        <label class="form-label" for="summernote">Post Content</label>
        <textarea name="post_content" id="summernote"><?php if(isset($_GET['edit'])) { echo $post['content']; } ?></textarea>
      </div>

      <div class="form-group">
        <label for="image">Post Image</label>
        <?php if(isset($_GET['edit'])) { displayUserImage("posts", "post_image", "post_id"); } ?>
        <input type="file" id="image" name="uploaded_image">
      </div>

      <div class="form-group">
        <label for="tags">Post Tags</label>
        <input type="text" name="post_tags" id="tags" class="form-control" value="<?php if(isset($_GET['edit'])) { echo $post['tags']; } ?>">
      </div>

      <div class="form-group">
        <label class="form-label" for="category">Post Category</label>
        <select name="post_category_id" id="category">
          <?php listItems("categories", ""); ?>
        </select>
      </div>

      <input type="submit" value="Edit Post" name="edit_post" class="btn btn-primary">

      </form>
    
      </div>
    </div>

  
<!-- End of Page Content -->
  </div>