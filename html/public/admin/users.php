<?php

require_once("../../includes/index.php");
if(!$session->is_admin_logged_in()){ redirect_to("login.php"); }

$all_users = User::find_all();
$current_page = !empty($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 10; // records per page
$total_count = count($all_users);

$pagination = new Pagination($current_page, $per_page, $total_count);

// get the records for this current page
$users = array_slice($all_users, $pagination->offset(), $per_page);

$photo_dir = '/public'.DS.'images/';
?>
<?php include_layout_template('admin_header.php'); ?>

<h2>Users</h2>

<?php echo alert($message, ALERT_INFO); ?>

<div class="container">
  <div class="row">
    <div class="col-md-12">
      <br />
      <!-- TODO: Create User Page
      <a href="create_user.php"><button class="btn btn-primary">Create A User</button></a>
      -->
      <br />
    </div>
    <div class="col-md-10 user-table-wrapper">
      <table class="table table-inverse">
        <thead>
          <tr>
            <th>Id</th>
            <th>Username</th>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <!-- TODO: Sign up date
            <th>Sign up date</th>
            -->
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>

        <?php foreach($users as $user): ?>
          <tr>
            <td><?php echo $user->id; ?></td>
            <td><?php echo $user->username; ?></td>
            <td><?php echo $user->first_name; ?></td>
            <td><?php echo $user->last_name; ?></td>
            <td><?php echo $user->email; ?></td>
            <td><a href="delete_user.php?id=<?php echo $user->id; ?>">Delete</a></td>
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
          <a class="page-link" href="<?php echo 'users.php?page='.$pagination->previous_page(); ?>" tabindex="-1">Previous</a>
        </li>

        <?php
          for($i=1; $i <= $pagination->total_pages(); $i++){
            echo ($i == $current_page)? "<li class='page-item active'>" : "<li class='page-item'>";
            echo "<a class='page-link' href='users.php?page=".$i."'>".$i."</a></li>";
          }
        ?>

        <li class="page-item <?php echo ($pagination->has_next_page()) ? '' : ' disabled'; ?>">
          <a class="page-link" href="<?php echo 'users.php?page='.$pagination->next_page();?>">Next</a>
        </li>
      </ul>
      <?php endif; ?>
    </nav>
  </div>
</div>
</div>

<?php include_layout_template('admin_footer.php'); ?>