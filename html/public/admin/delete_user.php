<?php

require_once("../../includes/index.php");
if(!$session->is_admin_logged_in()){ redirect_to("login.php"); }

if(!is_valid_number($_GET['id'])){
  $session->message("No Valid User ID was provided.");
  redirect_to('users.php');
}

$user = User::find_by_id($_GET['id']);
if($user && $user->delete()){
  $session->message("The user #id {$_GET['id']} was deleted.");
  redirect_to('users.php');
} else {
  $session->message("The user could not be deleted.");
  redirect_to('users.php');
}

?>


<?php if(isset($database)) $database->close_connection(); ?>