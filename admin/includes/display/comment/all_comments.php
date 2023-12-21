<?php
  if(isset($_GET['delete'])) {
    ApproveRejectOrDelete("comment", "delete");
  } else if(isset($_GET['approve'])) {
    ApproveRejectOrDelete("comment", "approve");
  } else if(isset($_GET['reject'])) {
    ApproveRejectOrDelete("comment", "reject");
  }

  if(isset($_POST['checkboxArray']) && isset($_POST['menu_apply'])) {
    applyOption("comments", "comment_status_id", "comment_id");
  }

  if(isset($_POST['update_all']) && isset($_POST['menu_apply'])) {
    applyOptionAll("comments", "comment_status_id");
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
    <button class="menu-btn"><a href="index.php?source=add_comments">Add New</a></button>
    
    <!-- View All Posts -->
<table class="table-style">
          <thead>
            <tr>
            <th><input type="checkbox" name="update_all"></th>
              <th>ID</th>
              <th>Post Title</th>
              <th>Comment</th>
              <th>Author</th>
              <th>Email</th>
              <th>Date</th>
              <th>Reply ID</th>
              <th>Status</th>
              <th>Admin</th>
            </tr>
          </thead>
          <tbody>
            <?php declareComments() ?>
          </tbody>
        </table>

  </form>
</div>
