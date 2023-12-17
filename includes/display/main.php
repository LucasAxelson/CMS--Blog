<!-- Page Content -->
<div class="container">

    <div class="row">

      <div class="col-md-8">
        <div>
            <h2 style="color: grey;">Main Page</h2>
                <div style="display: flex; flex-direction: row; justify-content: space-between; ">
                <?php if(isset($_SESSION['user_id'])) { 
                        echo "<h1 style=\"text-align: left\">Welcome " . $_SESSION['user_username'] . "!</h1>"; 
                } ?>
                
                <h2 style="text-align: right;">
                    <a href="index.php?source=create_post" style="color: green; text-decoration: none">
                        <span class="glyphicon glyphicon-plus" style="font-size: 25px;">
                        </span> 
                        Create a post 
                    </a>
                </h2>
        </div>
      </div>
<hr>
            <!-- Blog Posts -->
    <?php 
    if(isset($_POST["search_submit"])) {
        searchPosts();
    } else if (isset($_GET["category"])) {
        displayCategoryPosts();
    } else {
        displayPosts();
    }
    ?>

<!-- Pager -->
<ul class="pager">
    <li class="previous">
        <a href="#">&larr; Older</a>
    </li>
    <li class="next">
        <a href="#">Newer &rarr;</a>
    </li>
</ul>

</div>