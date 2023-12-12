<?php if(isset($_POST['log_in_submit'])) {
    // createAccount();
} ?>

<!-- Page Content -->
<div class="col-xl">
  <div class="col-lg-8">

    <div style="display:flex; flex-direction:column; align-items: center;">

      <div style="width: 75%;">
       <h1 style="margin-bottom: 3rem;" class="text-center">Log In</h1>

       <form action="index.php?source=login_page" method="POST" enctype="multipart/form-data">
 
       <div class="form-group">
          <label class="form-label" for="accountEmail">Email</label>
          <input type="email" name="account_email" id="accountEmail" class="form-control">
       </div>

       <div class="form-group">
          <label class="form-label" for="accountPassword">Password</label>
          <input type="password" name="account_password" id="accountPassword" class="form-control">
        </div>

        <input type="submit" value="Log In" name="log_in_submit" class="btn btn-primary">

      </form>

    <p class="text-center">Don't have a log in?? <a href="index.php?source=create_account">Create an Account!</a></p>
    </div>
<!-- End of Page Content -->
  </div>
  </div>