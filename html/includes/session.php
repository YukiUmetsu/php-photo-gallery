<?php

class Session{

  private $is_logged_in = false;
  private $is_admin_logged_in = false;
  public $user_id;
  public $admin_user_id;
  public $message;

  function __construct(){
    session_start();
    $this->check_message();
    $this->check_login();
    $this->check_admin_login();
  }

  private function check_message(){
    if(isset($_SESSION['message'])){
      $this->message = $_SESSION['message'];
      unset($_SESSION['message']);
    } else {
      $this->message = "";
    }
  }

  public function message($msg=""){
    if(!empty($msg)){
      $_SESSION['message'] = $msg;
    } else {
      return $this->message;
    }
  }

  private function check_login(){
    if(isset($_SESSION['user_id'])){
      $this->user_id = $_SESSION['user_id'];
      $this->is_logged_in = true;
    } else {
      unset($this->user_id);
      $this->is_logged_in = false;
    }
  }

  private function check_admin_login(){
    if(isset($_SESSION['admin_user_id'])){
      $this->admin_user_id = $_SESSION['admin_user_id'];
      $this->is_admin_logged_in = true;
    } else {
      unset($this->admin_user_id);
      $this->is_admin_logged_in = false;
    }
  }


  public function login($user){
    if($user){
      $this->user_id = $_SESSION['user_id'] = $user->id;
      $this->is_logged_in = true;
    }
  }

  public function admin_login($admin_user){
    if($admin_user){
      $this->admin_user_id = $_SESSION['admin_user_id'] = $admin_user->id;
      $this->is_logged_in = true;
    }
  }

  public function logout(){
    unset($_SESSION['user_id']);
    unset($this->user_id);
    $this->is_logged_in = false;
  }

  public function admin_logout(){
    unset($_SESSION['admin_user_id']);
    unset($this->admin_user_id);
    $this->is_admin_logged_in = false;
  }

  public function is_logged_in(){
    return $this->is_logged_in;
  }

  public function is_admin_logged_in(){
    return $this->is_admin_logged_in;
  }
}

$session = new Session();
$message = $session->message();