<?php require("../includes/server.php"); ?>
<?php require("includes/functions.php"); ?>
<?php require("includes/admin_header.php"); ?>

<body>

    <div id="wrapper">

    <!-- Navigation -->
    <?php require("includes/nav/navigation.php") ?>
     
      <div id="page-wrapper">

        <div class="container-fluid">

<!-- Page Heading -->
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">
                    Welcome to Admin
                    <small>Author</small>
                </h1>

                <!-- Declare new category form & function -->
                <?php if(isset($_POST["submit"])) {
                    $category = $_POST["cat_title"];
                    createCategory($category);
                }; ?>
                <div class="col-xs-6">
                    <form action="" method="POST">
                        <div class="form-group">
                            <input type="text" class="form-control" name="cat_title">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary" name="submit" value="Add Category">
                        </div>
                    </form>
                </div>
                <div class="col-xs-6">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Category</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php declareCategories(); ?>
                        </tbody>
                    </>
                </div>

            </div>
        </div>
    <!-- /.row -->

    
<?php require("includes/admin_footer.php"); ?>