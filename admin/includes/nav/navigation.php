<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.php">CMS Admin</a>
            </div>
            <!-- Top Menu Items -->
            <ul class="nav navbar-right top-nav">
                <!-- User Messages -->
                <?php require("includes/nav/userMessages.php") ?>
                <!-- User Notifications -->
                <?php require("includes/nav/userNotifications.php") ?>
                <!-- User Dropwdown Menu -->
                <?php require("includes/nav/userNav.php") ?>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <?php require("includes/nav/sideNav.php"); ?>
            <!-- /.navbar-collapse -->
        </nav>
