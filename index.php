
<?php require("includes/display/header.php"); ?>

<body>

    <!-- Navigation -->
    <?php require("includes/nav/navigation.php"); ?>

    <?php if(isset($_GET['log_out'])) {
        logoutUser();
    } ?>

            <!-- Main Column -->
            <?php require("includes/display.php") ?>

            <!-- Sidebar Widgets Column -->
            <?php require("includes/nav/sidebar.php") ?>
            
        <!-- Footer -->
        <?php require("includes/display/footer.php") ?>