<?php
 if(isset($_GET['edit'])) {  
   $user_id = $_GET['edit'];
 } else if(isset($_POST['edit_user'])) {  
    editComment($user_id);
 }
?>

<table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Username</th>
              <th>Legal Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Image</th>
              <th>Created</th>
              <th>Modified</th>
            </tr>
          </thead>
          <tbody>
            <?php seeUser() ?>
          </tbody>
        </table>

    <form action="index.php?source=edit_user<?php if(isset($_POST['selected_id'])) { echo "&edit=" . $_POST['selected_id']; } ?>" method="POST">
    <div>
      <label for="selectUser">Select a User:</label>
      <select style="border-radius: 5px; outline: black solid 1px" name="selected_id" id="selectUser">
        <?php listUsers() ?>
      </select>
      <button class="btn btn-info" style="font-size: 12px; padding: 1px 3px; outline: grey solid 1px;" name="select_submit" type="submit">Select</button>
      </div>
    </form>

<form action="index.php?source=edit_user&edit=<?php if(isset($_GET['user_id'])) { echo $user_id; } ?>" method="POST" enctype="multipart/form-data">
  
  <div class="form-group">
    <label class="form-label" for="userName">Username</label>
    <input type="text" name="user_username" id="userName" class="form-control">
  </div>
  
  <div class="form-group">
    <label class="form-label" for="legalName">Legal Name</label>
    <input type="text" name="user_legalname" id="legalName" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="email">Email</label>
    <input type="email" name="user_email" id="email" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="status">Status</label>
    <input type="text" name="user_status" id="status" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="access">Profile Picture</label>
    <input type="text" name="user_image" id="access" class="form-control">
  </div>

  <input type="submit" value="Edit User" name="edit_user" class="btn btn-primary">

</form>
