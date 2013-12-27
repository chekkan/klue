<?php
session_start();

require_once("lib/Page.php");

// if the user is not logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

$page = new Page();
$page->title = "New &lt; Messages &lt; Craften";
echo $page->header("Messages");
echo $page->breadcrumb(array("Home"=>"index.php", "Messages"=>"messages.php"));
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form">
	<h2>New Message</h2>
	<div class="form-group">
		<label for="to" class="col-sm-2 control-label">To</label>
		<div class="col-sm-10">
			<input type="text" name="to" id="to" placeholder="Email" class="form-control"  />
		</div>
	</div>
	<div class="form-group">
		<label for="message" class="col-sm-2 control-label">Message</label>
		<div class="col-sm-10">
			<textarea name="message" id="message" class="form-control"></textarea>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" name="send" value="Send" class="btn btn-default" />
		</div>
	</div>
</form>
<?php
echo $page->footer();
?>