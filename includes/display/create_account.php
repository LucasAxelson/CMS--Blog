<?php if(isset($_POST['create_account'])) {
    createAccount();
} ?>

<!-- Page Content -->
<div class="col-xl">

  <div class="col-lg-8">
  
    <div style="display:flex; flex-direction:column; align-items: center;">

      <h1 style="margin-bottom: 3rem;" class="text-center">Create Account</h1>
      
      <div style="width:75%;">

        <form action="index.php?source=create_account" method="POST" enctype="multipart/form-data">

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
            <label class="form-label" for="accountPassword">Password</label>
            <input type="password" name="account_password" id="accountPassword" class="form-control">
          </div>

          <div class="form-group">
            <label class="form-label" for="accountimage">Profile Picture</label>
            <input type="file" id="accountimage" name="account_image">
          </div>

          <input type="submit" value="Create Account" name="create_account" class="btn btn-primary">

        </form>
    
      </div>
    </div>

  
<!-- End of Page Content -->
  </div>
  </div>