<?php
  if(isset($_GET['delete'])) {
    ApproveRejectOrDelete("comment", "delete");
  } else if(isset($_GET['approve'])) {
    ApproveRejectOrDelete("comment", "approve");
  } else if(isset($_GET['reject'])) {
    ApproveRejectOrDelete("comment", "reject");
  }
?>

<table>
          <thead>
            <tr>
            <th>ID</th>
              <th>Post Title</th>
              <th>Comment</th>
              <th>Author</th>
              <th>Email</th>
              <th>Date</th>
              <th>Reply ID</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php declareComments() ?>
          </tbody>
        </table>