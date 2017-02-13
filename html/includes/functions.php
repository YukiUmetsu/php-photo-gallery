<?php

define("ALERT_SUCCESS", 1);
define("ALERT_INFO", 2);
define("ALERT_WARNING", 3);
define("ALERT_DANGER", 4);

function strip_zero_from_date($marked_string=""){
  // remove the marked zeros
  $no_zeros = str_replace('*0', '', $marked_string);

  // remove any remaining marks
  $cleaned_string = str_replace('*', '', $no_zeros);
  return $cleaned_string;
}

function redirect_to($location = NULL){
  if($location != NULL){
    header("Location: {$location}");
    exit();
  }
}

function output_message($message=""){
  if (!empty($message)){
    return "<p class=\"message\">{$message}</p>";
  } else {
    return "";
  }
}

function alert($message="", $alert_type){
  if (!empty($message)){
    switch ($alert_type) {
      case ALERT_SUCCESS:
        $html = "<div class='alert alert-success'>{$message}</div>";
        break;
      case ALERT_INFO:
        $html = "<div class='alert alert-info'>{$message}</div>";
        break;
      case ALERT_WARNING:
        $html = "<div class='alert alert-warning'>{$message}</div>";
        break;
      default:
        $html = "<div class='alert alert-danger'>{$message}</div>";
        break;
    }
    return $html;
  } else {
    return "";
  }
}

function __autoload($class_name){
  $class_name = strtolower($class_name);
  $file_name = "../includes/{$class_name}.php";
  if (file_exists($file_name)){
    require_once($file_name);
  } else {
    die("The file {$class_name}.php could not be found.");
  }
}

function include_layout_template($template=""){
  include(SITE_ROOT.DS."public".DS."layouts".DS.$template);
}

function log_action($action, $message=""){
  $logfile = SITE_ROOT . DS . 'logs' . DS . 'log.txt';
  $new = file_exists($logfile) ? false : true;

  if($handle = fopen($logfile,'a')){ //append

    // create logging content
    $timestamp = strftime("%Y-%m-%d %H:%M%S", time());
    $content = "{$timestamp} | {$action}: {$message}\n";

    // write in the file
    fwrite($handle, $content);
    fclose($handle);

    if($new){
      chmod($logfile, 0755);
    }
  } else {
    echo "Could not open log file for writing.";
  }
}

function sql_question_string($array){
  $string = "";
  foreach ($array as $element) {
    $string .= "?, ";
  }
  return rtrim($string, ", ");
}

function sql_update_string($attributes){
  $string = "";
  $keys = array_keys($attributes);
  foreach ($keys as $key) {
    // skip creating string if key was id
    if($key == "id"){
      continue;
    }
    $string .= "{$key} = ?, ";
  }
  return rtrim($string, ", ");
}

function size_as_text($size){
  if($size < 1024){
    return "{$size} bytes";
  } elseif($size < 1048576) {
    $size_kb = round($size/1024);
    return "{$size_kb} KB";
  } else {
    $size_mb = round($size/1048576,1);
    return "{$size_mb} MB";
  }
}

function is_valid_number($id){
  if(empty($id)) return false;
  // start from number, end with number
  if (preg_match("/^[0-9]+$/", $id)) return true;
}

function how_many_links($text){
  $link_num = 0;
  $link_num += substr_count($text, 'http://');
  $link_num += substr_count($text, 'https://');
  return $link_num;
}

function datetime_to_text($datetime=""){
  $unixdatetime = strtotime($datetime);
  return strftime("%B %d, %Y at %I:%M %p", $unixdatetime);
}