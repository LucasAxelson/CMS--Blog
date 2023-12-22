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

<h2>Edit Comment</h2>
<br>

        <table class="table-styled">
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
    <div class="div-form select">

      <p class="form-labels" for="selectComment">Select a Comment:</p>
      <select class="form-inputs" name="selected_id" id="selectComment">
        <option value="blank">Select an option</option>
        <?php listItems("comments", ""); ?>
      </select>
      <button class="select-btn" style="font-size: 12px; padding: 1px 3px; outline: grey solid 1px;" name="select_submit" type="submit">Select</button>
      
    </div>
  </form>

<br>

<div class="post">
<form action="index.php?source=edit_comments&edit=<?php if(isset($_GET['edit'])) { echo $_GET['edit']; } ?>" method="POST" enctype="multipart/form-data">
  
  <div class="div-form">
    <p class="form-labels" for="category">Select a Post:</p>
    <select class="form-inputs" name="post_id" id="category">
      <option value="blank">Select an option</option>
      <?php listItems("posts", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <label class="form-labels" for="title">Comment Email</label>
    <input type="text" placeholder="Insert new email" name="comment_email" class="form-inputs" value="<?php if(isset($_GET['edit'])) { echo $comment['email']; } ?>">
  </div>
  
  <div class="div-form">
    <label class="form-labels" for="title">Comment Author</label>
    <select name="comment_author" id="authorComment" class="form-inputs">
      <option value="blank">Select an option</option>
      <?php listItems("users", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <label class="form-labels" for="content">Comment Content</label>
    <textarea placeholder="Insert content" name="comment_content" id="content" class="form-inputs" rows="3"><?php if(isset($_GET['edit'])) { echo $comment['content']; } ?></textarea>
  </div>

  <div class="div-btn">
   <input type="submit" value="Edit comment" name="edit_comment" class="form-btn">
  </div>

</form>
</div>