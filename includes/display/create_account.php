<?php if(isset($_POST['create_account'])) {
    createAccount();
} ?>

<!-- Page Content -->
<div class="col-xl">

  <div class="col-lg-8">
  
    <div class="login-div">

      <h1 class="login-title">Create Account</h1>
      
        <form action="index.php?source=create_account" method="POST" enctype="multipart/form-data">

          <div class="inner-create inner-divs">
            <label class="login-labels" for="accountName">Username</label>
            <input type="text" name="account_username" id="accountName" class="login-inputs">
          </div>
  
         <div class="inner-create inner-divs">
            <label class="login-labels" for="accountLegalName">Legal Name</label>
           <input type="text" name="account_legal_name" id="accountLegalName" class="login-inputs">
         </div>

          <div class="inner-create inner-divs">
            <label class="login-labels" for="accountEmail">Email</label>
            <input type="email" name="account_email" id="accountEmail" class="login-inputs">
          </div>

          <div class="inner-create inner-divs">
            <label class="login-labels" for="accountPassword">Password</label>
            <input type="password" name="account_password" id="accountPassword" class="login-inputs">
          </div>

          <div class="inner-create inner-divs">
            <label class="login-labels" for="accountimage">Profile Picture</label>
            <input class="file-input" type="file" id="accountimage" name="uploaded_image">
          </div>

          <div class="inner-create inner-divs">
            <input type="submit" value="Create Account" name="create_account" class="login-btn">
          </div>

        </form>
    
      </div>
    </div>

  
<!-- End of Page Content -->
  </div>
  </div>