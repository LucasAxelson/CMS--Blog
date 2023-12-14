<?php
  $comment_id = $_GET['edit'];
  if(isset($_POST['edit_comment'])) {  
    editComment($comment_id);
  }
?>

<table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Post Title</th>
              <th>Comment</th>
              <th>Author</th>
              <th>Email</th>
              <th>Date</th>
              <th>Status</th>
              <th>Responded to</th>
            </tr>
          </thead>
          <tbody>
            <?php seeComment() ?>
          </tbody>
        </table>

<form action="index.php?source=edit_comments&edit=<?php echo $comment_id ?>" method="POST" enctype="multipart/form-data">
  
  <div class="form-group">
    <label class="form-label" for="category">Select a Post:</label>
    <select name="post_id" id="category">
    <?php listItems("posts", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="title">Comment Email</label>
    <input type="text" name="comment_email" class="form-control">
  </div>
  
  <div class="form-group">
    <label class="form-label" for="title">Comment Author</label>
    <select name="comment_author" id="authorComment">
    <?php listItems("users", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="content">Comment Content</label>
    <textarea name="comment_content" id="content" class="form-control" rows="3"></textarea>
  </div>

  <input type="submit" value="Edit comment" name="edit_comment" class="btn btn-primary">

</form>
