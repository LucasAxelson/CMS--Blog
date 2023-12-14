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
                    <small><?php echo $_SESSION['user_username'] ?></small>
                </h1>
                <ol class="breadcrumb">
                    <li>
                        <i class="fa fa-dashboard"></i>  <a href="index.php">Dashboard</a>
                    </li>
                    <li class="active">
                        <i class="fa fa-home"></i> <a href="../index.php">Main Page</a>
                    </li>
                </ol>
