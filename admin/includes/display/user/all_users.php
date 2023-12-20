<?php
  if(isset($_GET['delete'])) {
    ApproveRejectOrDelete("user", "delete");
  }
  if(isset($_GET['approve'])) {
    ApproveRejectOrDelete("user", "approve");
  }
  if(isset($_GET['reject'])) {
    ApproveRejectOrDelete("user", "reject");
  }
?>
 
<table class="table-style">
          <thead>
            <tr>
            <th>ID</th>
              <th>Username</th>
              <th>Legal Name</th>
              <th>Email</th>
              <th>Status</th>
              <th>Access</th>
              <th>Created</th>
              <th>Profile Picture</th>
            </tr>
          </thead>
          <tbody>
            <?php declareUsers() ?>
          </tbody>
        </table>