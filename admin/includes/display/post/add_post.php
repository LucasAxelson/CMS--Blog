<?php
  if(isset($_POST['create_post'])) {
    createPost("../");
  }
?>

<h2>Add Post</h2>
<br>

<div class="post">
<form action="index.php?source=add_post" method="POST" enctype="multipart/form-data">

  <div class="div-form">
    <p class="form-labels" for="title">Post Title</p>
    <input type="text" placeholder="Insert post title " id="title" name="post_title" class="form-inputs">
  </div>

  
  <div class="div-form">
    <p class="form-labels" for="title">Post Author</p>
    <select name="post_author" id="authorComment" class="form-inputs">
      <option value="blank">Select an option</option>
      <?php listItems("users", ""); ?>
    </select>
  </div>

  <div>
    <p class="form-labels" for="summernote">Post Content</p>    
    <textarea name="post_content" id="summernote"></textarea>
  </div>

  <div class="div-form">
    <p class="form-labels" for="image">Post Image</p>
    <input type="file" id="image" name="uploaded_image">
  </div>
  
  <div class="div-form">
    <p class="form-labels" for="tags">Post Tags</p>
    <input type="text" placeholder="Insert post tags" name="post_tags" id="tags" class="form-inputs">
  </div>

  <div class="div-form">
    <p class="form-labels" for="category">Post Category</p>
    <select name="post_category_id" id="category" class="form-inputs">
      <option value="blank">Select an option</option>
      <?php listItems("categories", ""); ?>
    </select>
  </div>

  <div class="div-btn">
    <input type="submit" value="Create Post" name="create_post" class="form-btn">
  </div>

</form>

</div>