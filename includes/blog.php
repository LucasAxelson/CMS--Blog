<div class="col-md-8">

<h1 class="page-header">
    Page Heading
    <small>Secondary Text</small>
</h1>

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