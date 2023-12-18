<?php
  if(isset($_GET['delete'])) {
    ApproveRejectOrDelete("post", "delete");
  } else if(isset($_GET['approve'])) {
    ApproveRejectOrDelete("post", "approve");
  } else if(isset($_GET['reject'])) {
    ApproveRejectOrDelete("post", "reject");
  }
?>

        <table>
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
