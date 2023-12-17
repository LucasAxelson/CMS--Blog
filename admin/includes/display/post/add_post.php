<?php
  if(isset($_POST['create_post'])) {
    createPost();
  }

?>

<form action="index.php?source=add_post" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label class="form-label" for="title">Post Title</label>
    <input type="text" id="title" name="post_title" class="form-control">
  </div>

  
  <div class="form-group">
    <label class="form-label" for="title">Post Author</label>
    <select name="post_author" id="authorComment">
      <?php listItems("users", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="summernote">Post Content</label>
    <textarea name="post_content" id="summernote"></textarea>
  </div>

  <div class="form-group">
    <label for="image">Post Image</label>
    <input type="file" id="image" name="post_image">
  </div>
  
  <div class="form-group">
    <label for="tags">Post Tags</label>
    <input type="text" name="post_tags" id="tags" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="category">Post Category</label>
    <select name="post_category_id" id="category">
      <?php listItems("categories", ""); ?>
    </select>
  </div>

  <input type="submit" value="Create Post" name="create_post" class="btn btn-primary">

</form>
