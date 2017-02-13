<?php
require_once('../includes/index.php');

if(!is_valid_number($_GET['id'])){
  $session->message("No Valid Photograph ID was provided.");
  redirect_to('index.php');
}

$photo = Image::find_by_id($_GET['id']);
if(!$photo){
  $session->message("The photo could not be located.");
  redirect_to('index.php');
}

if(isset($_POST['submit'])){
  $author = trim($_POST['author']);
  $body = trim($_POST['body']);

  // check the number of links included in the comments
  $link_num = how_many_links($author) + how_many_links($body);

  $new_comment = Comment::make($photo->id, $author, $body);

  if ($link_num > 3){
    $message = "too many links are included in your name or comments";
  } elseif($new_comment && $new_comment->save()){
    redirect_to("photo.php?id={$photo->id}");
  } else {
    $message = "There was an error that prevented the comment from being saved.";
  }
} else {
  $author = "";
  $body = "";
}

$comments = $photo->get_comments();
?>

<?php include_layout_template('header.php'); ?>
  <div class="container center">
    <?php echo alert($message, ALERT_INFO); ?>
    <a href="index.php"><< Back</a><br />

    <div class="col-lg-8 col-sm-8 text-center margin-auto photo-detail">
      <img src="<?php echo $photo->image_path(); ?>" alt="<?php echo $photo->caption; ?>">
      <p><?php echo $photo->caption; ?></p>
    </div>

    <!-- comment form -->
    <div id="comment-form" class="col-lg-8 col-sm-8 text-center margin-auto">
      <h4>What is on your mind?</h4>
      <form action="photo.php?id=<?php echo $photo->id; ?>" method="post">
        <table class="margin-auto">
          <tr class="form-group">
            <td><input type="text" name="author" class="form-control" value="<?php echo $author; ?>" placeholder="Your Name..."></td>
          </tr>
          <tr>
            <td><textarea name="body" cols="40" rows="3" class="form-control" placeholder="Your Comment here..."><?php echo $body; ?></textarea></td>
          </tr>
          <tr>
            <td><button type="submit" name="submit" class="btn btn-primary">Submit Comment</button></td>
          </tr>
        </table>
      </form>
    </div>
  </div>

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
            <li class="ui-state-default"><?php echo strip_tags($comment->body, '<strong><p>'); ?></li>
            <?php if($i + 1 < count($comments)) echo "<br />" ?>
          <?php endforeach; ?>
        </ul>
        <?php if(count($comments)==0) echo "<h4>0 comment found. <br />Wanna leave a comment?</h4><br />"; ?>
      </div>
    </div>
    </div>
<?php include_layout_template('footer.php'); ?>