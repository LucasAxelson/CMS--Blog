<?php 
$id = $_GET['edit'];

if(isset($_POST["edit_post"])) {
    editPost($id);
}
  ?>

<form action="index.php?source=edit_post&edit=<?php echo $id ?>" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label class="form-label" for="title">Post Title</label>
    <input type="text" id="title" name="post_title" class="form-control">
  </div>

  
  <div class="form-group">
    <label class="form-label" for="title">Post Author</label>
    <select name="post_author" id="authorComment">
      <?php listItems("users"); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="content">Post Content</label>
    <textarea name="post_content" id="content" class="form-control" rows="3"></textarea>
  </div>

  <div class="form-group">
    <label for="image">Post Image</label>
    <?php displayImage(); ?>
    <input type="file" id="image" name="post_image">
  </div>
  
  <div class="form-group">
    <label for="tags">Post Tags</label>
    <input type="text" name="post_tags" id="tags" class="form-control">
  </div>

  <div class="form-group">
    <label class="" for="category">Post Category</label>
    <select name="post_category_id" id="category">
      <?php listItems("categories") ?>
    </select>
  </div>

  <input type="submit" value="Edit Post" name="edit_post" class="btn btn-primary">

</form>
