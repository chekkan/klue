<?php

session_start();

require_once("lib/Database.php");
require_once("lib/User.php");
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
$page->add_css("styles/register.css");
echo $page->header();
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Register</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"error\">{$error_messages['main']}</p>";
	}?>
	<div class="input">
		<?php if (isset($error_messages['first_name'])) {
			echo "<p class=\"error\">{$error_messages['first_name']}</p>";
		}?>
		<label for="first_name">First Name</label>
		<input type="text" name="first_name" id="first_name"
			<?php if(isset($_POST['first_name'])) { echo "value=\"{$_POST['first_name']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['last_name'])) {
			echo "<p class=\"error\">{$error_messages['last_name']}</p>";
		}?>
		<label for="last_name">Last Name</label>
		<input type="text" name="last_name" id="last_name"
			<?php if(isset($_POST['last_name'])) { echo "value=\"{$_POST['last_name']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['email'])) {
			echo "<p class=\"error\">{$error_messages['email']}</p>";
		}?>
		<label for="email">Email</label>
		<input type="email" name="email" id="email"
			<?php if(isset($_POST['email'])) { echo "value=\"{$_POST['email']}\""; } ?>
		/>
	</div>
	<div class="input">
		<?php if (isset($error_messages['password'])) {
			echo "<p class=\"error\">{$error_messages['password']}</p>";
		}?>
		<label for="password">Password</label>
		<input type="password" name="password" id="password" />
	</div>
	<div class="input">
		<label for="confirm_password">Confirm Password</label>
		<input type="password" name="confirm_password" id="confirm_password" />
	</div>
	<div class="input">
		<?php if (isset($error_messages['date_of_birth'])) {
			echo "<p class=\"error\">{$error_messages['date_of_birth']}</p>";
		}?>
		<label for="date_of_birth">Date of Birth</label>
		<input type="date" name="date_of_birth" id="date_of_birth"
			<?php if(isset($_POST['date_of_birth'])) { echo "value=\"{$_POST['date_of_birth']}\""; } ?>
		/>
	</div>
	<div class="input">
		<input type="submit" name="register" value="Register" />
		<span>or <a href="login.php">Login</a></span>
	</div>
</form>

<?php
echo $page->footer();
?>