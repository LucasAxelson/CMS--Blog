<?php
  if(isset($_GET['delete'])) {
    ApproveRejectOrDelete("post", "delete");
  } else if(isset($_GET['approve'])) {
    ApproveRejectOrDelete("post", "approve");
  } else if(isset($_GET['reject'])) {
    ApproveRejectOrDelete("post", "reject");
  }

  if(isset($_POST['checkboxArray']) && isset($_POST['menu_apply'])) {
    applyOption();
  }
?>

<div class="menu-div">
  <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
    
  <!-- Top Menu -->
    <select class="form-inputs" name="bulkOptions" id="">
      <option value="blank">Select Options</option>
      <option value="draft_all">Draft All</option>
      <option value="approve_all">Approve All</option>
      <option value="reject_all">Reject All</option>
      <option value="delete_all">Delete All</option>
    </select>
    <input class="menu-btn" name="menu_apply" type="submit" value="Apply">
    <button class="menu-btn"><a href="index.php?source=add_post">Add New</a></button>

<!-- View All Posts -->
        <table class="table-style">
          <thead>
            <tr>
              <th>Select</th>
              <th>ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Date</th>
              <th>Category</th>
              <th>Status</th>
              <th>Image</th>
              <th>Tags</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody>
            <?php declarePosts() ?>
          </tbody>
        </table>
  </form>
</div>
