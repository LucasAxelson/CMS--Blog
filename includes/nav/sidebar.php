<div class="col-md-4">

<!-- Blog Search Well -->
<div class="well">
    <h4>Blog Search</h4>

    <form action="" method="POST">
        <div class="input-group">
          <input name="search" type="text" class="form-control">
         <span class="input-group-btn">
            <button name="search_submit" class="btn btn-default" type="submit">
                <span class="glyphicon glyphicon-search"></span>
            </button>
         </span>
        </div>
    </form> <!-- search form -->
    <!-- /.input-group -->
</div>

<!-- Blog Categories Well -->
<div class="well">
    <h4>Blog Categories</h4>
    <div class="row">
        <div class="col-lg-6">
            <ul class="list-unstyled">
                <!-- Display Categories from DB -->
                <?php displayCategories("LIMIT 5"); ?>
            </ul>
        </div>
        <!-- /.col-lg-6 -->
        <div class="col-lg-6">
            <ul class="list-unstyled">
            <?php displayCategories("LIMIT 5 OFFSET 5"); ?>
            </ul>
        </div>
        <!-- /.col-lg-6 -->
    </div>
    <!-- /.row -->
</div>

<!-- Side Widget Well -->
<?php require("includes/nav/widget.php") ?>

</div>

</div>
        <!-- /.row -->

        <hr>
