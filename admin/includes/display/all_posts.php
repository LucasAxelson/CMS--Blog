<?php
  if(isset($_GET['delete'])) {
    deletePost();
  } else if(isset($_GET['approve'])) {
     approvePost();
  } else if(isset($_GET['reject'])) {
    rejectPost();
  }
?>

        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Author</th>
              <th>Date</th>
              <th>Category</th>
              <th>Status</th>
              <th>Image</th>
              <th>Tags</th>
              <th>Comments</th>
            </tr>
          </thead>
          <tbody>
            <?php declarePosts() ?>
          </tbody>
        </table>
