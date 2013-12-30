<?php
session_start();

require_once("../lib/Page.php");
require_once("../../lib/Database.php");
require_once("../../lib/FormHelper.php");

// if the user is logged in, log them out.
if(isset($_SESSION['logged_in'])) {
	if($_SESSION['logged_in']) {
		header("Location: logout.php");
	}
}

if(isset($_POST['login'])) {
	//variables for saving error messages
	$error_messages = array();
	//save the fields into variables
	$email = $_POST['email'];
	$password = $_POST['password'];
	// make sure the email field is not empty
	if(empty($email)) {
		$error_messages['email'] = "This field cannot be empty.";
	}
	// make sure the password field is not empty
	if(empty($password)) {
		$error_messages['password'] = "This field cannot be empty.";
	}
	// if there are no errors
	if(empty($error_messages)) {
		// check to see if the email and password combination match.
		$sql = "SELECT id, level_id 
				FROM users 
				WHERE email=\"{$email}\" AND password='".sha1($password)."';";
		$result = $database->query($sql);
		if($database->num_rows($result) == 1) {
			// login user
			$user = $database->fetch_assoc($result);
			$_SESSION['user_id'] = $user['id'];
			$_SESSION['user_level_id'] = $user['level_id'];
			$_SESSION['logged_in'] = true;
			header("Location: index.php");
		}
		else {
			$error_messages['main'] = "Invalid login details. Please try again.";
		}
	}
}

$page = new Page();
$page->title = "Login";
$page->add_css("../css/login.css");
echo $page->header("Login");

$form = new FormHelper();
echo $form->start(array("heading" => "Login"));
echo $form->email(array("name" => "email", "id" => "Email", "label"=>"Email"));
echo $form->password(array("name" => "password", "id" => "Password", "label" => "Password"));
?>
	<div class="form-group">
		<input type="submit" name="login" value="Login" class="btn btn-default" />
		<span>or <a href="register.php" title="Register">Register</a></span>
	</div>
</form>

<?php
echo $page->footer();
?>