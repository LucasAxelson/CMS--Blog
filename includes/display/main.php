<!-- Page Content -->
<div class="container">

    <div class="row">

      <div class="col-md-8">
        <div>
            <h2 style="color: grey;">Main Page</h2>

                <?php if(isset($_SESSION['user_id'])) { 
                        echo "<h1>Welcome " . $_SESSION['user_username'] . "!</h1>"; 
                } ?>
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