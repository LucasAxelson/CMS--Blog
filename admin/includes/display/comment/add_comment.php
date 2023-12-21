<?php
  if(isset($_POST['create_comment'])) {
    createComment();
  }
?>

<h2>Add Comment</h2>
<br>

<div class="post">
<form action="index.php?source=add_comments" method="POST" enctype="multipart/form-data">  

  <div class="div-form">
    <label class="form-labels" for="category">Select a Post:</label>
    <select class="form-inputs" name="post_id" id="category">
      <?php listItems("posts", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <label class="form-labels" for="title">Comment Email</label>
    <input type="text" name="comment_email" class="form-inputs">
  </div>
  
  <div class="div-form">
    <label class="form-labels" for="title">Comment Author</label>
    <select class="form-inputs" name="comment_author" id="authorComment">
      <?php listItems("users", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <label class="form-labels" for="content">Comment Content</label>
    <textarea name="comment_content" id="content" class="form-inputs" rows="3"></textarea>
  </div>

  <div class="div-btn">
    <input type="submit" value="Create a comment" name="create_comment" class="form-btn">
  </div>

</form>
</div>