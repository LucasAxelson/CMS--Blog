<?php
  if(isset($_POST['create_user'])) {
    createUser("../");
  }
?>

<h2>Add User</h2>
<br>

<div class="user">
<form action="index.php?source=add_user" method="POST" enctype="multipart/form-data">

<div class="div-form">
    <p class="form-labels" for="userName">Username</p>
    <input class="form-inputs" type="text" name="account_username" id="userName">
  </div>
  
  <div class="div-form">
    <p class="form-labels" for="legalName">Legal Name</p>
    <input class="form-inputs" type="text" name="account_legal_name" id="legalName">
  </div>

  <div class="div-form">
    <p class="form-labels" for="email">Email</p>
    <input class="form-inputs" type="email" name="account_email" id="email">
  </div>

  <div class="div-form">
    <p class="form-labels" for="authorStatus">Status</p>
    <select class="form-inputs" name="account_status" id="authorStatus">
      <option value="blank">Select an option</option>
      <?php listItems("status", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <p class="form-labels" for="authorAccess">Access</p>
    <select class="form-inputs" name="account_access" id="authorAccess">
      <option value="blank">Select an option</option>
      <?php listItems("access", ""); ?>
    </select>
  </div>

  <div class="div-form">
    <p class="form-labels" for="access">Profile Picture</p>
    <input type="file" id="image" name="uploaded_image">
  </div>

  <div class="div-btn">
    <input type="submit" value="Add User" name="create_user" class="form-btn">
  </div>

</form>

</div>