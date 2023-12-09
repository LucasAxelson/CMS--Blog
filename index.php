<?php require("includes/header.php"); ?>

<body>

    <!-- Navigation -->
    <?php
        require("includes/navigation.php");
    ?>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <!-- Blog Entries Column -->
            <?php
                require("includes/blog.php")
            ?>

            <!-- Blog Sidebar Widgets Column -->
            <?php
                require("includes/sidebar.php")
            ?>
            
        </div>
        <!-- /.row -->

        <hr>


<?php
    require("includes/footer.php")
?>