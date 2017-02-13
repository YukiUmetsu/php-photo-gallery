<?php

require_once("../../includes/index.php");
if(!$session->is_admin_logged_in()){ redirect_to("login.php"); }

if(!is_valid_number($_GET['id'])){
  $session->message("No valid photo ID was provided");
  redirect_to("list_photos.php");
}

$photo = Image::find_by_id($_GET['id']);
if(!$photo){
  $session->message("The photo could not be located");
  redirect_to("list_photos.php");
}

$comments = $photo->get_comments();

?>

<?php include_layout_template('admin_header.php'); ?>

<a href="list_photos.php"><< Back</a><br /><br />
<?php echo alert($message, ALERT_INFO); ?>

<h2>Comments on <?php echo $photo->filename; ?></h2>

<!-- List comments -->
    <div class="container comment-container">
      <div class="col-lg-6 col-sm-6 text-center">
      <div class="well">
        <h3>Comments</h3>
        <hr>
        <ul id="sortable" class="list-unstyled ui-sortable">
          <?php foreach($comments as $comment): ?>
            <strong class="pull-left primary-font comment-author-name"><?php echo htmlentities($comment->author); ?></strong>
            <small class="pull-right text-muted">
               <span class="glyphicon glyphicon-time comment-created-at"><?php echo datetime_to_text($comment->created_at); ?></span>
            </small>
            </br>
            <li class="ui-state-default"><?php echo strip_tags($comment->body, '<strong><p>'); ?><br />
            <a href="delete_comment.php?id=<?php echo $comment->id; ?>"><button class="btn btn-primary">Delete Comment</button></a>
            </li>
            <?php if($i + 1 < count($comments)) echo "<br />" ?>
          <?php endforeach; ?>
        </ul>
        <?php if(count($comments)==0) echo "<h4>0 comment found. <br />Wanna leave a comment?</h4><br />"; ?>
      </div>
    </div>


<?php include_layout_template('admin_footer.php'); ?>