<?php require_once("../../includes/index.php"); ?>
<?php
  $session->admin_logout();
  redirect_to("login.php");
  exit();