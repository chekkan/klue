<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Models/User.php");

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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form">
	<h2>Edit Profile</h2>
	<?php if(isset($error_messages['main']))
		echo "<p class=\"text-danger\">{$error_messages['main']}</p>"; 
	?>
	<div class="form-group">
		<label for="first_name" class="col-sm-2 control-label">First Name</label>
		<div class="col-sm-10">
			<input type="text" name="first_name" id="first_name" class="form-control"
				<?php if(isset($_POST['first_name'])) { echo "value=\"{$_POST['first_name']}\""; }
				else { echo "value=\"{$user->first_name}\""; } ?>
			/>
			<?php if(isset($error_messages['first_name']))
				echo "<p class=\"text-danger\">{$error_messages['first_name']}</p>"; 
			?>
		</div>
	</div>
	<div class="form-group">
		<label for="last_name" class="col-sm-2 control-label">Last Name</label>
		<div class="col-sm-10">
			<input type="text" name="last_name" id="last_name" class="form-control"
				<?php if(isset($_POST['last_name'])) { echo "value=\"{$_POST['last_name']}\""; }
				else { echo "value=\"{$user->last_name}\""; } ?>
			/>
			<?php if(isset($error_messages['last_name']))
				echo "<p class=\"text-danger\">{$error_messages['last_name']}</p>"; 
			?>
		</div>
	</div>
	<div class="form-group">
		<label for="email" class="col-sm-2 control-label">Email</label>
		<div class="col-sm-10">
			<input type="email" name="email" id="email" class="form-control"
				<?php if(isset($_POST['email'])) { echo "value=\"{$_POST['email']}\""; }
				else { echo "value=\"{$user->email}\""; } ?>
			/>
			<?php if(isset($error_messages['email']))
				echo "<p class=\"text-danger\">{$error_messages['email']}</p>"; 
			?>
		</div>
	</div>
	<div class="form-group">
		<label for="date_of_birth" class="col-sm-2 control-label">Date of Birth</label>
		<div class="col-sm-10">
			<input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
				<?php if(isset($_POST['date_of_birth'])) { echo "value=\"{$_POST['date_of_birth']}\""; }
				else { echo "value=\"{$user->date_of_birth}\""; } ?>
			/>
			<?php if(isset($error_messages['date_of_birth']))
				echo "<p class=\"text-danger\">{$error_messages['date_of_birth']}</p>"; 
			?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" name="save" value="Save" class="btn btn-default" />
		</div>
	</div>
</form>
<?php
echo $page->footer();
?>