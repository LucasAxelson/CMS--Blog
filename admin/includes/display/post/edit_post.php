<?php 
if(isset($_POST["edit_post"])) {
    editPost("", $_GET['edit']);
}

if(isset($_GET['edit'])) {  
  $post = pullItem("posts, status", "status.status_id = posts.post_status_id AND posts.post_id = " . $_GET['edit']);
};

if(isset($_POST['select_submit'])) { 
  seeSelectedItem("posts");
}; 
?>

<form action="index.php?source=edit_post<?php if(isset($_POST['select_submit'])) { echo "&edit=" . $_POST['selected_id']; } ?>" method="POST">
    <div>
      <label for="selectUser">Select a User:</label>
      <select style="border-radius: 5px; outline: black solid 1px" name="selected_id" id="selectUser">
        <?php listItems("posts", ""); ?>
      </select>
      <button class="btn btn-info" style="font-size: 12px; padding: 1px 3px; outline: grey solid 1px;" name="select_submit" type="submit">Select</button>
    </div>
</form>
<br>
<form action="index.php?source=edit_post&edit=<?php if(isset($_GET['edit'])) { echo $_GET['edit']; } ?>" method="POST" enctype="multipart/form-data">

  <div class="form-group">
    <label class="form-label" for="title">Post Title</label>
    <input placeholder="Insert new title" type="text" id="title" value="<?php if(isset($_GET['edit'])) { echo $post['title']; } ?>" name="post_title" class="form-control">
  </div>

  
  <div class="form-group">
    <label class="form-label" for="title">Post Author</label>
    <select name="post_author" id="authorComment">
      <?php listItems("users", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="summernote">Post Content</label>
    <textarea placeholder="Insert content" name="post_content" id="summernote" class="form-control" rows="3"><?php if(isset($_GET['edit'])) { echo $post['content']; } ?></textarea>
  </div>

  <div class="form-group">
    <label for="image">Post Image</label>
    <?php if(isset($_GET['edit'])) { displayImage("posts", "post_image", "post_id"); }?>
    <input type="file" id="image" name="post_image">
  </div>
  
  <div class="form-group">
    <label for="tags">Post Tags</label>
    <input placeholder="Insert new tags" type="text" name="post_tags" id="tags" class="form-control" value="<?php if(isset($_GET['edit'])) { echo $post['tags']; } ?>">
  </div>

  <div class="form-group">
    <label class="" for="category">Post Category</label>
    <select name="post_category_id" id="category">
      <?php listItems("categories", "") ?>
    </select>
  </div>

  <input type="submit" value="Edit Post" name="edit_post" class="btn btn-primary">

</form>
