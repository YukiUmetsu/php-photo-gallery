<?php
require_once('../includes/index.php');

$all_photos = Image::find_all();

$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 8; // records per page
$total_count = count($all_photos);

$pagination = new Pagination($current_page, $per_page, $total_count);

// get the records for this current page
$photos = array_slice($all_photos, $pagination->offset(), $per_page);

?>

<?php include_layout_template('header.php'); ?>

<div class="container">
  <div class="row">
    <?php foreach ($photos as $photo) : ?>
      <div class="col-lg-3 col-md-4 col-xs-6 thumb">
        <a href="photo.php?id=<?php echo $photo->id;?>" class="thumbnail">
          <img src="<?php echo $photo->image_path(); ?>" alt="<?php echo $photo->caption; ?>" class="img-thumbnail">
        </a>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- pagination navigation -->
<nav aria-label="Page navigation">
  <?php if($pagination->total_count > 1): ?>
  <ul class="pagination justify-content-end">

    <li class="page-item
      <?php echo ($pagination->has_previous_page()) ? '' : ' disabled'; ?>">
      <a class="page-link" href="<?php echo 'index.php?page='.$pagination->previous_page(); ?>" tabindex="-1">Previous</a>
    </li>

    <?php
      for($i=1; $i <= $pagination->total_pages(); $i++){
        echo ($i == $current_page)? "<li class='page-item active'>" : "<li class='page-item'>";
        echo "<a class='page-link' href='index.php?page=".$i."'>".$i."</a></li>";
      }
    ?>

    <li class="page-item <?php echo ($pagination->has_next_page()) ? '' : ' disabled'; ?>">
      <a class="page-link" href="<?php echo 'index.php?page='.$pagination->next_page();?>">Next</a>
    </li>
  </ul>
  <?php endif; ?>
</nav>
<?php include_layout_template('footer.php'); ?>