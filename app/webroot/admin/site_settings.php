<?php
session_start();

require_once("../../lib/Settings.php");
require_once("../../lib/AdminPage.php");
require_once("../../lib/Models/User.php");

// check if the user is logged in
if(isset($_SESSION['logged_in'])) {
	// and that he is an administrator
	if(!User::is_admin($_SESSION['user_id'])) {
		header("Location: ../login.php");
	}
}
else {
	header("Location: ../login.php");
}

if(isset($_POST['save'])) {
	$error_messages = array();
	//validate the fields
	if(empty($_POST['site_name'])) {
		$error_messages['site_name'] = "Required field cannot be empty.";
	}
	
	// if no errors
	if(empty($error_messages)) {
		$settings = new Settings();
		$settings->site_name = $_POST['site_name'];
		$settings->site_description = $_POST['site_description'];
		$saved = $settings->save();
		if($saved) {
			$message = "Saved.";
		}
		else {
			$error_messages['main'] = "Something went wrong. Try again later.";
		}
	}
}

// get the site settings
$settings = new Settings();

$page = new AdminPage();
$page->title = "Site Settings &lt; Administration";
echo $page->header("Dashboard");
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Site Settings</h2>
	<?php if(isset($error_messages['main'])) { echo "<p class=\"error\">{$error_messages['main']}</p>"; }
		else { if(isset($message)) { echo "<p>{$message}</p>"; } }
	?>
	<div class="input">
		<?php if(isset($error_messages['site_name'])) { echo "<p class=\"error\">{$error_messages['site_name']}</p>"; } ?>
		<label for="site_name">Name</label>
		<input type="text" name="site_name" id="site_name"
			<?php if(isset($_POST['site_name'])) { echo "value=\"{$_POST['site_name']}\" "; }
			else { echo "value=\"{$settings->site_name}\" "; } ?>
		/>
	</div>
	<div class="input">
		<?php if(isset($error_messages['site_description'])) { echo "<p class=\"error\">{$error_messages['site_description']}</p>"; } ?>
		<label for="site_description">Description</label>
		<textarea name="site_description" id="site_description"><?php 
			if(isset($_POST['site_description'])) { echo $_POST['site_description']; }
			else { echo $settings->site_description; } 
		?></textarea>
	</div>
	<div>
		<input type="submit" name="save" value="Save" />
	</div>
</form>
<?php
echo $page->footer();
?>