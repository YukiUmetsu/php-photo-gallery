<?php

require_once("../../includes/index.php");
if(!$session->is_logged_in()){ redirect_to("login.php"); }

if(!is_valid_number($_GET['id'])){
  $session->message("No Valid Photograph ID was provided.");
  redirect_to('list_photos.php');
}

$photo = Image::find_by_id($_GET['id']);
if($photo && $photo->destroy()){
  $session->message("The photo {$photo->filename} was deleted.");
  redirect_to('list_photos.php');
} else {
  $session->message("The photo could not be deleted.");
  redirect_to('list_photos.php');
}

?>


<?php if(isset($database)) $database->close_connection(); ?>