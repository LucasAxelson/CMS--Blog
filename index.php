
<?php require("includes/display/header.php"); ?>

<body>

    <!-- Navigation -->
    <?php require("includes/nav/navigation.php"); ?>

    <?php if(isset($_GET['log_out'])) {
            logoutUser();
          } 
          if(isset($_POST["suggest_submit"])) {
            createCategory($_POST['suggest']);
          }
?>

            <!-- Main Column -->
            <?php require("includes/display.php") ?>

            <!-- Sidebar Widgets Column -->
            <?php require("includes/nav/sidebar.php") ?>
            
        <!-- Footer -->
        <?php require("includes/display/footer.php") ?>