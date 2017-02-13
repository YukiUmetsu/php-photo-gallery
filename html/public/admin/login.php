<?php

require_once("../../includes/index.php");

if($session->is_logged_in()){ redirect_to("index.php"); }

if (isset($_POST['submit'])){
  // form submitted
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  // Authenticate User
  $found_user = User::authenticate($username, $password);

  if($found_user){
    // create session
    $session->login($found_user);
    // log who logged in
    log_action('Login', "{$found_user->username} logged in.");
    redirect_to("index.php");
  } else {
    $message = "username/password combination incorrect.";
  }
} else {
  // form has not been submitted.
  $username = "";
  $password = "";
}

?>

<html>
  <head>
    <title>PHP Gallery</title>
    <link rel="stylesheet" href="../stylesheets/main.css" media="all" type="text/css" />
  </head>
  <body>
    <div id="header">
      <h1>Login</h1>
    </div>
    <div id="main">
      <?php $message ? output_message($message) : NULL; ?>
      <form action="login.php" method="post">
        <table>
          <tr>
            <td>Username: </td>
            <td>
              <input type="text" name="username" maxlength="30" value="<?php echo $username; ?>" />
            </td>
          </tr>
          <tr>
            <td>Password: </td>
            <td>
              <input type="password" name="password" maxlength="30" value="<?php echo $password; ?>" />
            </td>
          </tr>
          <tr>
            <td colspan="2">
              <input type="submit" name="submit" value="Login" />
            </td>
          </tr>
        </table>
      </form>
    </div>
    <div id="footer">Copyright <?php echo date("Y", time()); ?> Yuki Umetsu</div>
  </body>
</html>
<?php if(isset($database)) { $database->close_connection(); } ?>