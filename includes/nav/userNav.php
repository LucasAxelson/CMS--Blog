<div class="nav navbar-nav navbar-right">
<li class="nav-item dropdown pull-right">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <?php echo $_SESSION['user_username'] ?> <b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li>
            <a href="index.php?source=profile_page<?php echo "&page=" . $_SESSION["user_id"] ?>"><i class="fa fa-fw fa-user"></i> Profile</a>
        </li>
        <li>
            <a href="#"><i class="fa fa-fw fa-gear"></i> Settings</a>
        </li>
        <li class="divider"></li>
        <li>
            <a href="index.php?source=main_page&log_out"><i class="fa fa-fw fa-power-off"></i> Log Out</a>
        </li>
        </li>
    </ul>
</li>
</div>
