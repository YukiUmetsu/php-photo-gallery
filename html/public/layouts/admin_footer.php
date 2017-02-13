  </div>
<div id="footer">Copyright <?php echo date("Y", time()); ?>, Yuki Umetsu</div>
</body>
<?php if(isset($database)){ $database->close_connection(); }?>