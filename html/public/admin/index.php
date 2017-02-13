<?php

require_once("../../includes/index.php");
require_once("../../includes/session.php");

if(!$session->is_admin_logged_in()){ redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>
    <h2>Menu</h2>
    <?php echo alert($message, ALERT_INFO); ?>
    <ul>
      <li><a href="list_photos.php">List Photos</a></li>
      <li><a href="users.php">List Users</a></li>
      <li><a href="logfile.php">View Log File</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
<?php include_layout_template('admin_footer.php'); ?>