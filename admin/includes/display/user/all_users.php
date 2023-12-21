<?php
  if(isset($_GET['delete'])) {
    ApproveRejectOrDelete("user", "delete");
  }
  if(isset($_GET['approve'])) {
    ApproveRejectOrDelete("user", "approve");
  }
  if(isset($_GET['reject'])) {
    ApproveRejectOrDelete("user", "reject");
  }
  if(isset($_POST['checkboxArray']) && isset($_POST['menu_apply'])) {
    applyOption("users", "user_status_id", "user_id");
  }
  if(isset($_POST['update_all']) && isset($_POST['menu_apply'])) {
    applyOptionAll("users", "user_status_id");
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
    <button class="menu-btn"><a href="index.php?source=add_user">Add New</a></button>
    </div>

  <!-- View All Posts -->
<table class="table-style">
          <thead>
            <tr>
            <th><input type="checkbox" name="update_all"></th>
              <th>ID</th>
              <th>Username</th>
              <th>Legal Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Access</th>
              <th>Created</th>
              <th>Profile Picture</th>
              <th>Admin</th>
            </tr>
          </thead>
          <tbody>
            <?php declareUsers() ?>
          </tbody>
        </table>
  

</form>
