<?php if(isset($_POST['user_comment_submit'])) { createComment(); } ?>

<div class="well">
  
  <h4>Leave a Comment:</h4>
  
  <form action="" method="POST">
  
    <div class="form-group">
      <textarea name="comment_content" class="form-control" rows="3"></textarea>
    </div>
    
    <button type="submit" name="user_comment_submit" class="btn btn-primary">Submit</button>
  
  </form>
</div>