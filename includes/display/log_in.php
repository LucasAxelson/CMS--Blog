<?php if(isset($_POST["login_submit"])) {
    loginUser();
} ?>

<!-- Page Content -->
  <div class="col-lg-8">

      <div class="login-div">
        <h1 class="login-title">Log In</h1>

        <form action="index.php?source=login_page" method="POST" enctype="multipart/form-data">
 
          <div class="inner-login inner-divs">
           <label class="login-labels" for="loginEmail">Email</label>
           <input type="email" name="login_email" id="loginEmail" class="login-inputs">
          </div>

          <div class="inner-login inner-divs">
           <label class="login-labels" for="loginPassword">Password</label>
           <input type="password" name="login_password" id="loginPassword" class="login-inputs">
          </div>

          <div class="inner-login inner-divs">
            <input type="submit" value="Log In" name="login_submit" class="login-btn">
          </div>

        </form>
        <br>
        <p class="text-center">Don't have a log in?? <a href="index.php?source=create_account">Create an Account!</a></p>
      </div>
<!-- End of Page Content -->
  </div>
  </div>