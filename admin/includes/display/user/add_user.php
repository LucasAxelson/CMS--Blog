<?php
  if(isset($_POST['create_user'])) {
    createUser();
  }

?>

<form action="index.php?source=add_user" method="POST" enctype="multipart/form-data">

<div class="form-group">
    <label class="form-label" for="userName">Username</label>
    <input type="text" name="user_username" id="userName" class="form-control">
  </div>
  
  <div class="form-group">
    <label class="form-label" for="legalName">Legal Name</label>
    <input type="text" name="user_legal_name" id="legalName" class="form-control">
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
    <input type="file" id="image" name="user_image">
  </div>

  <input type="submit" value="Add User" name="create_user" class="btn btn-primary">

</form>
