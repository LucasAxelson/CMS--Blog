<?php
  if(isset($_GET['delete'])) {
    deleteComment();
  }
  if(isset($_GET['approve'])) {
    approveComment();
  }
  if(isset($_GET['reject'])) {
    rejectComment();
  }
?>

<table class="table table-bordered table-hover">
          <thead>
            <tr>
            <th>ID</th>
              <th>Post Title</th>
              <th>Comment</th>
              <th>Author</th>
              <th>Email</th>
              <th>Date</th>
              <th>Responded to</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php declareComments() ?>
          </tbody>
        </table>