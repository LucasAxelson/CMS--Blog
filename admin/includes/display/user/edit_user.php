<?php
 if(isset($_GET['edit'])) {  
  $user = pullUser($_GET['edit']);
 };
  
 if(isset($_POST['edit_user'])) {  
  $user_id = $_GET['edit'];
  editUser($user_id);
 } 
 
 if(isset($_POST['select_submit'])) { 
    seeSelectedUser();
  }; 
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
            <?php if(isset($_GET['edit'])) { seeUser(); } ?>
          </tbody>
        </table>

    <form action="index.php?source=edit_user<?php if(isset($_POST['select_submit'])) { echo "&edit=" . $_POST['selected_id']; } ?>" method="POST">
    <div>
      <label for="selectUser">Select a User:</label>
      <select style="border-radius: 5px; outline: black solid 1px" name="selected_id" id="selectUser">
        <?php listItems("users", ""); ?>
      </select>
      <button class="btn btn-info" style="font-size: 12px; padding: 1px 3px; outline: grey solid 1px;" name="select_submit" type="submit">Select</button>
      </div>
    </form>

<form action="index.php?source=edit_user<?php if(isset($_GET['edit'])) { echo "&edit=" . $_GET['edit'] . ""; } ?>" method="POST" enctype="multipart/form-data">
  
  <div class="form-group">
    <label class="form-label" for="userName">Username</label>
    <input type="text" placeholder="Insert new username" value="<?php echo $user['username'] ?>"  name="user_username" id="userName" class="form-control">
  </div>
  
  <div class="form-group">
    <label class="form-label" for="legalName">Legal Name</label>
    <input type="text" placeholder="Insert new name" value="<?php echo $user['legal_name'] ?>"  name="user_legal_name" id="legalName" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="email">Email</label>
    <input type="email" placeholder="Insert new email" value="<?php echo $user['email'] ?>"  name="user_email" id="email" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="password">Password</label>
    <input type="password" placeholder="Insert new password" name="user_password" id="password" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="authorStatus">Status</label>
    <select name="user_status" id="authorStatus">
      <?php $stmt = "user_id = " . $user['status_id']; 
            listItems("status", "" ); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="authorAccess">Access</label>
    <select name="user_access" id="authorAccess">
      <?php listItems("access", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="userImage">Profile Picture</label>
    <?php displayImage("users", "user_image", "user_id"); ?>
    <input type="file" name="user_image" id="userImage">
  </div>

  <input type="submit" value="Edit User" name="edit_user" class="btn btn-primary">

</form>
