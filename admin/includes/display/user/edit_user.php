<?php
 if(isset($_GET['edit'])) {  
  $user = pullItem("users, status", "status.status_id = users.user_status_id AND users.user_id = " . $_GET['edit']);
 };
  
 if(isset($_POST['edit_user'])) {  
  $user_id = $_GET['edit'];
  editUser($user_id);
 } 
 
 if(isset($_POST['select_submit'])) { 
    seeSelectedItem("users");
  }; 
?>

<h2>Edit User</h2>
<br>

<table class="table-style">
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
    <div class="div-form select">
      <label class="form-labels" for="selectUser">Select a User:</label>
      <select class="form-inputs" name="selected_id" id="selectUser">
        <option value="blank">Select an option</option>
        <?php listItems("users", ""); ?>
      </select>
      <button class="select-btn" name="select_submit" type="submit">Select</button>
      </div>
    </form>

    <hr>

    <div class="user">

<form action="index.php?source=edit_user<?php if(isset($_GET['edit'])) { echo "&edit=" . $_GET['edit'] . ""; } ?>" method="POST" enctype="multipart/form-data">
  
  <div class="div-form">
    <label class="form-labels" for="userName">Username</label>
    <input type="text" placeholder="Insert new username" value="<?php if(isset($_GET['edit'])) { echo $user['username']; } ?>"  name="user_username" id="userName" class="form-inputs">
  </div>
  
  <div class="div-form">
    <label class="form-labels" for="legalName">Legal Name</label>
    <input type="text" placeholder="Insert new name" value="<?php if(isset($_GET['edit'])) { echo $user['legal_name']; } ?>"  name="user_legal_name" id="legalName" class="form-inputs">
  </div>

  <div class="div-form">
    <label class="form-labels" for="email">Email</label>
    <input type="email" placeholder="Insert new email" value="<?php if(isset($_GET['edit'])) { echo $user['email']; } ?>"  name="user_email" id="email" class="form-inputs">
  </div>

  <div class="div-form">
    <label class="form-labels" for="password">Password</label>
    <input type="password" placeholder="Insert new password" name="user_password" id="password" class="form-inputs">
  </div>

  <div class="div-form">
    <label class="form-labels" for="authorStatus">Status</label>
    <select class="form-inputs" name="user_status" id="authorStatus">
      <option value="blank">Select an option</option>
      <?php listItems("status", "" ); ?>
    </select>
  </div>

  <div class="div-form">
    <label class="form-labels" for="authorAccess">Access</label>
    <select class="form-inputs" name="user_access" id="authorAccess">
      <option value="blank">Select an option</option>
      <?php listItems("access", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <label class="form-labels" for="userImage">Profile Picture</label>
    <?php if(isset($_GET['edit'])) { displayImage("users", "user_image", "user_id"); } ?>
    <input type="file" name="account_image" id="userImage">
  </div>

  <div class="div-btn">
    <input type="submit" value="Edit User" name="edit_user" class="form-btn">
  </div>

</form>

</div>