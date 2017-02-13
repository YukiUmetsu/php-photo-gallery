<?php

require_once("../../includes/index.php");
require_once("../../includes/session.php");

if(!$session->is_logged_in()){ redirect_to("login.php"); }
?>

<?php include_layout_template('admin_header.php'); ?>

<?php

// CREATE
// $user = new User("newuser", "password", "first_name", "last_name", "email@gmail.com");
// $user->create();


// DELETE
// $user = User::find_by_id(14);
// $result = $user->delete();

// if($result){
//   echo "delete true";
// } else {
//   echo "delete false";
// }


// UPDATE
$user = User::find_by_id(10);
$user->password = "123";
$result = $user->update();
if($result){
  echo "update true";
} else {
  echo "update false";
}
?>
<?php include_layout_template('admin_footer.php'); ?>
















<style>
<!--
.form_input_label {
  width: 150px !important;
}
.form_input_input {
  width: 400px !important;
}
.form_input_vertical {
  display: block !important;
}
.form_input_horizontal {
  display: inline-block !important;
}
div.right_block {
  width: 400px;
  word-break: break-all;
  display: inline-block;
}
.required_color {
  background-color : #fdd;
}
#content_form {
  padding-left  : 50px;
  background-color : #fff !important;
  padding:0px !important;
}

-->
</style>
<div id="content_form">
<h2>セミリタイヤ可能な教育の案内とともに伝説のYouTube教材を無料で手に入れる！</h2>

<form action="https://herosjourney.jp/p/r/VC2xluPT" enctype="multipart/form-data" id="UserItemForm" method="post" accept-charset="utf-8">
<input type="hidden" name="_method" value="POST"/>
<input name="data[User][mail]" id="Usermail" value="" class="form_input_input" type="text"/>

<input  type="submit" value="無料で手に入れる！"/></div>
<input type="hidden" id="server_url" value="https://herosjourney.jp/"/>
<!-- ▼リファラ -->
<input type="hidden" name="data[User][referer_form_url]" value="" id="UserRefererFormUrl"/>
<input type="hidden" name="data[User][referer_url]" value="" id="UserRefererUrl"/>

<script type="text/javascript">
<!--
if (document.referrer.length !=0 ){
  if(document.getElementById("UserRefererUrl"))
  {
    document.getElementById("UserRefererUrl").value=document.referrer;
  }
}
if (document.getElementById("UserRefererFormUrl"))
{
  document.getElementById("UserRefererFormUrl").value=location.href;
}
//-->
</script>
<!-- ▲リファラ -->

</form>
</div>




<a href="https://enhance-inc.leadpages.co/leadbox/143f051f3f72a2%3A102380732346dc/5711312218750976/" target="_blank">
  <img class="hvr-grow-shadow" src="img/order-button-bt6.png" onmouseover="this.src='img/order-button-bt6-hover.png'" onmouseout="this.src='img/order-button-bt6.png'" />
</a>
<script data-leadbox="143f051f3f72a2:102380732346dc" data-url="https://enhance-inc.leadpages.co/leadbox/143f051f3f72a2%3A102380732346dc/5711312218750976/" data-config="%7B%7D" type="text/javascript" src="https://enhance-inc.leadpages.co/leadbox-1486757954.js"></script>
<script src="https://herosjourney.jp/js/validation.js"></script>

