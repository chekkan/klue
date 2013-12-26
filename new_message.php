<?php
session_start();

require_once("lib/Page.php");

// if the user is not logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

$page = new Page();
$page->title = "New &lt; Messages &lt; Craften";
echo $page->header();
echo $page->breadcrumb(array("Home"=>"index.php", "Messages"=>"messages.php"));
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>New Message</h2>
	<div class="input">
		<label for="to">To</label>
		<input type="text" name="to" id="to" placeholder="Email"  />
	</div>
	<div class="input">
		<label for="message">Message</label>
		<textarea name="message" id="message"></textarea>
	</div>
	<div class="input">
		<input type="submit" name="send" value="Send" />
	</div>
</form>
<?php
echo $page->footer();
?>