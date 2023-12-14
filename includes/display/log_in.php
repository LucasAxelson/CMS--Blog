<?php if(isset($_POST["login_submit"])) {
    loginUser();
} ?>

<!-- Page Content -->
  <div class="col-lg-8">

    <div style="display:flex; flex-direction:column; align-items: center;">

      <div style="width: 75%;">
       <h1 style="margin-bottom: 3rem;" class="text-center">Log In</h1>

       <form action="index.php?source=login_page" method="POST" enctype="multipart/form-data">
 
        <div class="form-group">
           <label class="form-label" for="loginEmail">Email</label>
           <input type="email" name="login_email" id="loginEmail" class="form-control">
        </div>

        <div class="form-group">
           <label class="form-label" for="loginPassword">Password</label>
           <input type="password" name="login_password" id="loginPassword" class="form-control">
        </div>

         <input type="submit" value="Log In" name="login_submit" class="btn btn-primary">

      </form>
    <br>
    <p class="text-center">Don't have a log in?? <a href="index.php?source=create_account">Create an Account!</a></p>
    </div>
<!-- End of Page Content -->
  </div>
  </div>