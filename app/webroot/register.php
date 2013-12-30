<?php

session_start();

require_once("../lib/Page.php");
require_once("../../lib/Database.php");
require_once("../lib/Models/User.php");
require_once("../../lib/FormHelper.php");

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
$page->add_css("../css/register.css");
echo $page->header("Register");

$form = isset($error_messages) ? new FormHelper($error_messages) : new FormHelper();
echo $form->start(array("heading"=>"Register"));
echo $form->text(array("label"=>"First Name", "name"=>"first_name", "id"=>"FirstName"));
echo $form->text(array("label"=>"Last Name", "name"=>"last_name", "id"=>"LastName"));
echo $form->email(array("label"=>"Email", "name"=>"email", "id"=>"Email"));
echo $form->password(array("label"=>"Password", "name"=>"password", "id"=>"Password"));
echo $form->password(array("label"=>"Confirm Password", "name"=>"confirm_password", "id"=>"ConfirmPassword"));
echo $form->date(array("label"=>"Date of Birth", "name"=>"date_of_birth", "id"=>"DateOfBirth"));
?>
	<div class="form-group">
		<input type="submit" name="register" value="Register" class="btn btn-primary" />
		<span>or <a href="login.php">Login</a></span>
	</div>
</form>

<?php
echo $page->footer();
?>