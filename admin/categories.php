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
                <?php require("includes/admin_head.php"); ?>
                <!-- Create categpry when requested -->
                <?php if(isset($_POST["submit"])) {
                    $category = $_POST["cat_title"];
                    createCategory($category);
                }; ?>
                <div class="col-xs-6">                    
                <!-- Delete Category when requested -->
                <?php if(isset($_GET["delete"])) {
                    $id = $_GET["delete"];
                    if (deleteCategory($id)) {
                        deleteCategory($id);
                    } else {
                        declareError("Category in use.");
                    }
                }; ?>
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
                      <!-- Declare all Categories -->
                            <?php declareCategories(); ?>
                        </tbody>
                    </>
                </div>

            </div>
        </div>
    <!-- /.row -->

    
<?php require("includes/admin_footer.php"); ?>