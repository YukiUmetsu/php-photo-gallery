<?php

require_once("../../includes/index.php");
if(!$session->is_admin_logged_in()){ redirect_to("login.php"); }

$max_file_size = 1048576; //10MB
$message = "";

if(isset($_POST['submit'])&&isset($_FILES['file_upload'])){
  $photo = new Image();
  $photo->caption = $_POST['caption'];
  $photo->attach_file($_FILES['file_upload']);

  if($photo->save()){
    $session->message("Photograph was uploaded successfully.");
    redirect_to('list_photos.php');
  } else {
    $message = join("<br />", $photo->errors);
  }
}

?>
<?php include_layout_template('admin_header.php'); ?>
<?php echo alert($message, ALERT_INFO); ?>
<form action="photo_upload.php" enctype="multipart/form-data" method="POST">
  <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>">
  <p><input type='file' name="file_upload" accept='image/*' /></p>
  <p>Caption: <input type="text" name="caption" value=""></p>
  <input type="submit" name="submit" value="Upload">
</form>

<?php include_layout_template('admin_footer.php'); ?>