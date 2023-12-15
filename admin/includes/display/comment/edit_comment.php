<?php

if(isset($_GET['edit'])) {  
  $comment = pullItem("comments, status", "comments.comment_id = " . $_GET['edit']);
 };

 if(isset($_POST['edit_comment'])) {  
  editComment($_GET['edit']);
  }

if(isset($_POST['select_submit'])) { 
  seeSelectedItem("comments");
}; 
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
            <?php if(isset($_GET['edit'])) { seeComment(); } ?>
          </tbody>
        </table>

<form action="index.php?source=edit_comments<?php if(isset($_POST['select_submit'])) { echo "&edit=" . $_POST['selected_id']; } ?>" method="POST">
    <div>
      <label for="selectComment">Select a Comment:</label>
      <select style="border-radius: 5px; outline: black solid 1px" name="selected_id" id="selectComment">
        <?php listItems("comments", ""); ?>
      </select>
      <button class="btn btn-info" style="font-size: 12px; padding: 1px 3px; outline: grey solid 1px;" name="select_submit" type="submit">Select</button>
      </div>
</form>
<br>
<form action="index.php?source=edit_comments&edit=<?php if(isset($_GET['edit'])) { echo $_GET['edit']; } ?>" method="POST" enctype="multipart/form-data">
  
  <div class="form-group">
    <label class="form-label" for="category">Select a Post:</label>
    <select name="post_id" id="category">
    <?php listItems("posts", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="title">Comment Email</label>
    <input type="text" placeholder="Insert new email" name="comment_email" class="form-control" value="<?php if(isset($_GET['edit'])) { echo $comment['email']; } ?>">
  </div>
  
  <div class="form-group">
    <label class="form-label" for="title">Comment Author</label>
    <select name="comment_author" id="authorComment">
    <?php listItems("users", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="content">Comment Content</label>
    <textarea placeholder="Insert content" name="comment_content" id="content" class="form-control" rows="3"><?php if(isset($_GET['edit'])) { echo $comment['content']; } ?></textarea>
  </div>

  <input type="submit" value="Edit comment" name="edit_comment" class="btn btn-primary">

</form>
