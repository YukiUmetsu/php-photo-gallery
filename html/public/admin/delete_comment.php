<?php

require_once("../../includes/index.php");
if(!$session->is_logged_in()){ redirect_to("login.php"); }

if(!is_valid_number($_GET['id'])){
  $session->message("No Valid comment ID was provided.");
  redirect_to('list_photos.php');
}

$comment = Comment::find_by_id($_GET['id']);
if($comment && $comment->delete()){
  $session->message("The comment was deleted.");
  redirect_to("comments.php?id={$comment->photo_id}");
} else {
  $session->message("The comment could not be deleted.");
  redirect_to('list_photos.php');
}

?>


<?php if(isset($database)) $database->close_connection(); ?>