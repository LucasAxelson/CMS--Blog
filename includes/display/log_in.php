<?php if(isset($_POST['create_account'])) {
    createAccount();
} ?>

<!-- Page Content -->
<div class="col-xl">
<div class="col-lg-8">

<div style="margin: 2rem;">

    <form action="index.php?source=login_page" method="POST" enctype="multipart/form-data">

      <div class="form-group">
        <label class="form-label" for="accountName">Username</label>
        <input type="text" name="account_username" id="accountName" class="form-control">
      </div>
  
      <div class="form-group">
        <label class="form-label" for="accountLegalName">Legal Name</label>
        <input type="text" name="account_legal_name" id="accountLegalName" class="form-control">
      </div>

      <div class="form-group">
        <label class="form-label" for="accountEmail">Email</label>
        <input type="email" name="account_email" id="accountEmail" class="form-control">
      </div>

      <div class="form-group">
        <label class="form-label" for="accountimage">Profile Picture</label>
        <input type="file" id="accountimage" name="account_image">
      </div>

      <input type="submit" value="Create Account" name="create_account" class="btn btn-primary">

    </form>
  
<!-- End of Page Content -->
  </div>
  </div>