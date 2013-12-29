<?php
session_start();

require_once("../../lib/Database.php");
require_once("../lib/Page.php");

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
?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form">
	<h2>Login</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
	}?>
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
		<input type="submit" name="login" value="Login" class="btn btn-default" />
		<span>or <a href="register.php" title="Register">Register</a></span>
	</div>
</form>

<?php
echo $page->footer();
?>