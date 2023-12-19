<?php
  if(isset($_POST['create_user'])) {
    createAccount("../");
  }

?>

<form action="index.php?source=add_user" method="POST" enctype="multipart/form-data">

<div class="form-group">
    <label class="div-label" for="userName">Username</label>
    <input type="text" name="account_username" id="userName" class="form-control">
  </div>
  
  <div class="form-group">
    <label class="div-label" for="legalName">Legal Name</label>
    <input type="text" name="account_legal_name" id="legalName" class="form-control">
  </div>

  <div class="form-group">
    <label class="div-label" for="email">Email</label>
    <input type="email" name="account_email" id="email" class="form-control">
  </div>

  <div class="form-group">
    <label class="form-label" for="authorStatus">Status</label>
    <select name="account_status" id="authorStatus">
      <?php listItems("status", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="authorAccess">Access</label>
    <select name="account_access" id="authorAccess">
      <?php listItems("access", ""); ?>
    </select>
  </div>

  <div class="form-group">
    <label class="form-label" for="access">Profile Picture</label>
    <input type="file" id="image" name="account_image">
  </div>

  <input type="submit" value="Add User" name="create_user" class="btn btn-primary">

</form>
