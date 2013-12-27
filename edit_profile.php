<?php
session_start();

require_once("lib/User.php");
require_once("lib/Page.php");

// make sure the user is logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

if(isset($_POST['save'])) {
	$error_messages = array();
	// validate the form fields
	if(empty($_POST['first_name'])) {
		$error_messages['first_name'] = "Required field cannot be empty.";
	}
	if(empty($_POST['last_name'])) {
		$error_messages['last_name'] = "Required field cannot be empty.";
	}
	if(empty($_POST['email'])) {
		$error_messages['email'] = "Required field cannot be empty.";
	}
	if(empty($_POST['date_of_birth'])) {
		$error_messages['date_of_birth'] = "Required field cannot be empty.";
	}
	
	// if there was no errors
	if(empty($error_messages)) {
		// save the details
		$user = new User();
		$user->id = $_SESSION['user_id'];
		$user->email = $_POST['email'];
		$user->date_of_birth = $_POST['date_of_birth'];
		$user->first_name = $_POST['first_name'];
		$user->last_name = $_POST['last_name'];
		$saved = $user->save();
		if($saved) {
			header("Location: profiles.php?id={$user->id}");
		}
		else {
			$error_messages['main'] = "Something went wrong. Try again later.";
		}
	}
}

// get the logged in user's details
$user = User::find_by_id($_SESSION['user_id']);

$page = new Page();
$page->title = "Edit &lt; Profile &lt; Craften";
echo $page->header("Profile");
echo $page->breadcrumb(array("Home"=>"index.php", "Profiles"=>"profiles.php", $user->full_name()=>"profiles.php?id={$user->id}"));
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Edit Profile</h2>
	<?php if(isset($error_messages['main']))
		echo "<p class=\"error\">{$error_messages['main']}</p>"; 
	?>
	<div class="input">
		<?php if(isset($error_messages['first_name']))
			echo "<p class=\"error\">{$error_messages['first_name']}</p>"; 
		?>
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" id="first_name"
			<?php if(isset($_POST['first_name'])) { echo "value=\"{$_POST['first_name']}\""; }
			else { echo "value=\"{$user->first_name}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if(isset($error_messages['last_name']))
			echo "<p class=\"error\">{$error_messages['last_name']}</p>"; 
		?>
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name"
			<?php if(isset($_POST['last_name'])) { echo "value=\"{$_POST['last_name']}\""; }
			else { echo "value=\"{$user->last_name}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if(isset($error_messages['email']))
			echo "<p class=\"error\">{$error_messages['email']}</p>"; 
		?>
		<label for="email">Email</label>
		<input type="email" name="email" id="email"
			<?php if(isset($_POST['email'])) { echo "value=\"{$_POST['email']}\""; }
			else { echo "value=\"{$user->email}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if(isset($error_messages['date_of_birth']))
			echo "<p class=\"error\">{$error_messages['date_of_birth']}</p>"; 
		?>
		<label for="date_of_birth">Date of Birth</label>
		<input type="date" name="date_of_birth" id="date_of_birth"
			<?php if(isset($_POST['date_of_birth'])) { echo "value=\"{$_POST['date_of_birth']}\""; }
			else { echo "value=\"{$user->date_of_birth}\""; } ?>
		/>
	</div>
	<div class="input">
		<input type="submit" name="save" value="Save" class="btn btn-default" />
	</div>
</form>
<?php
echo $page->footer();
?>