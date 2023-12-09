<?php
  if(isset($_POST['create_comment'])) {
    createComment();
  }
?>

<form action="index.php?source=add_comments" method="POST" enctype="multipart/form-data">
  
  <div class="form-group">
    <label class="form-label" for="category">Select a Post:</label>
    <select name="post_id" id="category">
      <?php listPosts() ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="title">Comment Email</label>
    <input type="text" name="comment_email" class="form-control">
  </div>
  
  <div class="form-group">
    <label class="form-label" for="title">Comment Author</label>
    <input type="text" name="comment_author" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="content">Comment Content</label>
    <textarea name="comment_content" id="content" class="form-control" rows="3"></textarea>
  </div>

  <input type="submit" value="Create a comment" name="create_comment" class="btn btn-primary">

</form>
