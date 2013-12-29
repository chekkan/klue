<?php

session_start();

require_once("lib/Database.php");
require_once("lib/Models/User.php");
require_once("lib/Page.php");

if(isset($_POST['register'])) {
	// array to save errors
	$error_messages = array();
	
	// save the form fields into variables
	$first_name = $_POST['first_name'];
	$last_name = $_POST['last_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$confirm_password = $_POST['confirm_password'];
	$date_of_birth = $_POST['date_of_birth'];
	
	// make sure none of them are empty
	if(empty($first_name)) {
		$error_messages['first_name'] = "This is a required field and cannot be empty.";
	}
	if(empty($last_name)) {
		$error_messages['last_name'] = "This is a required field and cannot be empty.";
	}
	if(empty($email)) {
		$error_messages['email'] = "This is a required field and cannot be empty.";
	}
	if(empty($password)) {
		$error_messages['password'] = "This is a required field and cannot be empty.";
	}
	if(empty($date_of_birth)) {
		$error_messages['date_of_birth'] = "This is a required field and cannot be empty.";
	}
	
	// if no errors were created
	if(empty($error_messages)) {
		// make sure the passwords match
		if($password != $confirm_password) {
			$error_messages['password'] = "Passwords does not match.";
		}
		// make sure that the email is not taken
		$sql = "SELECT id FROM users WHERE email = \"{$email}\";";
		$result = $database->query($sql);
		if($database->num_rows($result) > 0) {
			$error_messages['email'] = "Email address already taken.";
		}
		
		// if still no errors, register the user
		if(empty($error_messages)) {
			$user = new User();
			$user->email = $email;
			$user->password = $password;
			$user->first_name = $first_name;
			$user->last_name = $last_name;
			$user->date_of_birth = $date_of_birth;
			$user->level_id = 2;
			$registered = $user->save();
			if($registered) {
				// redirect the user to login page.
				header("Location: login.php");
			}
			else {
				$error_messages['main'] = "Could not register. Try again later.";
			}
		}
	}
}

$page = new Page();
$page->title = "Register";
$page->add_css("css/register.css");
echo $page->header("Register");
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form">
	<h2>Register</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
	}?>
	<div class="form-group">
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" id="first_name" class="form-control"
			<?php if(isset($_POST['first_name'])) { echo "value=\"{$_POST['first_name']}\""; } ?>
		/>
		<?php if (isset($error_messages['first_name'])) {
			echo "<p class=\"text-danger\">{$error_messages['first_name']}</p>";
		}?>
	</div>
	<div class="form-group">
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name"class="form-control"
			<?php if(isset($_POST['last_name'])) { echo "value=\"{$_POST['last_name']}\""; } ?>
		/>
		<?php if (isset($error_messages['last_name'])) {
			echo "<p class=\"text-danger\">{$error_messages['last_name']}</p>";
		}?>
	</div>
	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" name="email" id="email" class="form-control"
			<?php if(isset($_POST['email'])) { echo "value=\"{$_POST['email']}\""; } ?>
		/>
		<?php if (isset($error_messages['email'])) {
			echo "<p class=\"text-danger\">{$error_messages['email']}</p>";
		}?>
	</div>
	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" name="password" id="password" class="form-control" />
		<?php if (isset($error_messages['password'])) {
			echo "<p class=\"text-danger\">{$error_messages['password']}</p>";
		}?>
	</div>
	<div class="form-group">
		<label for="confirm_password">Confirm Password</label>
		<input type="password" name="confirm_password" id="confirm_password" class="form-control" />
	</div>
	<div class="form-group">
		<label for="date_of_birth">Date of Birth</label>
		<input type="date" name="date_of_birth" id="date_of_birth" class="form-control"
			<?php if(isset($_POST['date_of_birth'])) { echo "value=\"{$_POST['date_of_birth']}\""; } ?>
		/>
		<?php if (isset($error_messages['date_of_birth'])) {
			echo "<p class=\"text-danger\">{$error_messages['date_of_birth']}</p>";
		}?>
	</div>
	<div class="form-group">
		<input type="submit" name="register" value="Register" class="btn btn-primary" />
		<span>or <a href="login.php">Login</a></span>
	</div>
</form>

<?php
echo $page->footer();
?>