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

<h2>Edit Post</h2>
<br>

<form action="index.php?source=edit_post<?php if(isset($_POST['select_submit'])) { echo "&edit=" . $_POST['selected_id']; } ?>" method="POST">
    <div class="div-form select">
    
      <p class="form-labels" for="selectUser">Select a Post:</p>
      <select class="form-inputs" name="selected_id" id="selectUser">
        <?php listItems("posts", ""); ?>
      </select>
    
      <button class="select-btn" name="select_submit" type="submit">Select</button>
    
    </div>
</form>

<br>

<div class="post">
<form action="index.php?source=edit_post&edit=<?php if(isset($_GET['edit'])) { echo $_GET['edit']; } ?>" method="POST" enctype="multipart/form-data">

  <div class="div-form">
    <p class="form-labels" for="title">Post Title</p>
    <input placeholder="Insert new title" type="text" id="title" value="<?php if(isset($_GET['edit'])) { echo $post['title']; } ?>" name="post_title" class="form-inputs">
  </div>

  
  <div class="div-form">
    <p class="form-labels" for="title">Post Author</p>
    <select class="form-inputs" name="post_author" id="authorComment">
      <?php listItems("users", ""); ?>
    </select>
  </div>

  <div>
    <p class="form-labels" for="summernote">Post Content</p>
    <textarea name="post_content" id="summernote"><?php if(isset($_GET['edit'])) { echo $post['content']; } ?></textarea>
  </div>

  <div class="div-form">
    <p class="form-labels" for="image">Post Image</p>
    <?php if(isset($_GET['edit'])) { displayImage("posts", "post_image", "post_id"); }?>
    <input type="file" id="image" name="uploaded_image">
  </div>
  
  <div class="div-form">
    <p class="form-labels" for="tags">Post Tags</p>
    <input placeholder="Insert new tags" type="text" name="post_tags" id="tags" class="form-inputs" value="<?php if(isset($_GET['edit'])) { echo $post['tags']; } ?>">
  </div>

  <div class="div-form">
    <p class="form-labels" for="category">Post Category</p>
    <select class="form-inputs" name="post_category_id" id="category">
      <?php listItems("categories", "") ?>
    </select>
  </div>

  <div class="div-btn">
    <input type="submit" value="Edit Post" name="edit_post" class="form-btn">
  </div>

</form>
</div>