<?php

require_once("../../includes/index.php");
if(!$session->is_logged_in()){ redirect_to("login.php"); }

$photos = Image::find_all();
$photo_dir = '/public'.DS.'images/';
?>
<?php include_layout_template('admin_header.php'); ?>

<h2>Photographs</h2>

<?php echo alert($message, ALERT_INFO); ?>

<div class="container">
  <div class="row">
    <div class="col-md-1">
    </div>
    <div class="col-md-10">
      <table class="table table-inverse">
        <thead>
          <tr>
            <th>Image</th>
            <th>File Name</th>
            <th>Caption</th>
            <th>Size</th>
            <th>Type</th>
            <th>Uploaded by</th>
            <th>Comments</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach($photos as $photo): ?>
          <tr>
            <td><img src="<?php echo $photo_dir . $photo->filename; ?>" alt="<?php echo $photo->caption; ?>" width='100'></td>
            <td><?php echo $photo->filename; ?></td>
            <td><?php echo $photo->caption; ?></td>
            <td><?php echo size_as_text($photo->size); ?></td>
            <td><?php echo $photo->type; ?></td>
            <td><?php echo $photo->get_username(); ?></td>
            <td>
              <a href="comments.php?id=<?php echo $photo->id; ?>">
                <?php echo count($photo->get_comments()); ?>
              </a>
            </td>
            <td><a href="delete_photo.php?id=<?php echo $photo->id; ?>">Delete</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
      <a href="photo_upload.php">upload a new photo</a>
    </div>
  <div class='col-md-1'>
  </div>
</div>
</div>

<?php include_layout_template('admin_footer.php'); ?>