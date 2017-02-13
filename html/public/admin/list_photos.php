<?php

require_once("../../includes/index.php");
if(!$session->is_admin_logged_in()){ redirect_to("login.php"); }

$all_photos = Image::find_all();
$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10; // records per page
$total_count = count($all_photos);

$pagination = new Pagination($current_page, $per_page, $total_count);

// get the records for this current page
$photos = array_slice($all_photos, $pagination->offset(), $per_page);

$photo_dir = '/public'.DS.'images/';
?>
<?php include_layout_template('admin_header.php'); ?>

<h2>Photographs</h2>

<?php echo alert($message, ALERT_INFO); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <br />
      <a href="photo_upload.php"><button class="btn btn-primary">Upload a New Photo</button></a>
      <br />
    </div>
    <div class="col-md-10 photo-table-wrapper">
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
    </div>

  <div class='col-md-12'>
    <!-- pagination navigation -->
    <nav aria-label="Page navigation">
      <?php if($pagination->total_count > 1): ?>
      <ul class="pagination justify-content-end">

        <li class="page-item
          <?php echo ($pagination->has_previous_page()) ? '' : ' disabled'; ?>">
          <a class="page-link" href="<?php echo 'list_photos.php?page='.$pagination->previous_page(); ?>" tabindex="-1">Previous</a>
        </li>

        <?php
          for($i=1; $i <= $pagination->total_pages(); $i++){
            echo ($i == $current_page)? "<li class='page-item active'>" : "<li class='page-item'>";
            echo "<a class='page-link' href='list_photos.php?page=".$i."'>".$i."</a></li>";
          }
        ?>

        <li class="page-item <?php echo ($pagination->has_next_page()) ? '' : ' disabled'; ?>">
          <a class="page-link" href="<?php echo 'list_photos.php?page='.$pagination->next_page();?>">Next</a>
        </li>
      </ul>
      <?php endif; ?>
    </nav>
  </div>
</div>
</div>

<?php include_layout_template('admin_footer.php'); ?>