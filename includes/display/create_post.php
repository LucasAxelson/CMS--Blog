<!-- Page Content -->
  <div class="col-lg-8">
  
    <div style="display:flex; flex-direction:column; align-items: center;">

      <h1 style="margin: 3rem;" class="text-center">Create a Post</h1>
      
      <div style="width:75%;">

      <form action="index.php?source=add_post" method="POST" enctype="multipart/form-data">

<div class="form-group">
  <label class="form-label" for="title">Post Title</label>
  <input type="text" id="title" name="post_title" class="form-control">
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
    
      </div>
    </div>

  
<!-- End of Page Content -->
  </div>