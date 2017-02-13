<?php require_once("../../includes/index.php"); ?>
<?php
  $session->logout();
  redirect_to("login.php");
  exit();