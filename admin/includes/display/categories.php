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
                    
                <!-- Edit Category when requested -->
                <?php if(isset($_POST["edit"])) {
                    $id = $_GET["edit"];
                    $title = $_POST["title"];
                    
                    editCategory($id, $title);
                }; ?>
       
                <?php  if(isset($_GET['edit'])) { require("includes/display/edit_category.php"); } ?>
                </div>
                
                <!-- Category table -->
                <div class="col-xs-6">
                    <table>
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
