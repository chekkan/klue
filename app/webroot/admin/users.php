<?php

session_start();

require_once("../../lib/AdminPage.php");
require_once("../../lib/Models/User.php");
require_once("../../lib/Models/Level.php");
require_once("../../../lib/FormHelper.php");

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

if(isset($_GET['id']) && isset($_GET['action'])) {

	// validate the id
	
	// check if the user wants to edit the user details
	if($_GET['action'] == "edit") {
	
		// check if the form was submitted
		if(isset($_POST['save'])) {
			// get the user details
			$user = User::find_by_id($_GET['id']);
			
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
			if(empty($_POST['level'])) {
				$error_messages['level'] = "Required field cannot be empty.";
			}
			
			// check if the admin was the last administrator
			$sql = "SELECT id FROM users WHERE level_id=1;";
			$result = $database->query($sql);
			if(($user->level_id == 1) && ($database->num_rows($result) <= 1)) {
				$error_messages['level'] = "Atleast 1 Administrator is required.";
			}
			
			// if there was no errors
			if(empty($error_messages)) {
				$user = new User();
				$user->id = $_GET['id'];
				$user->first_name = $_POST['first_name'];
				$user->last_name = $_POST['last_name'];
				$user->email = $_POST['email'];
				$user->date_of_birth = $_POST['date_of_birth'];
				$user->level_id = $_POST['level'];
				$saved = $user->save();
				if($saved) {
					header("Location: users.php?id=$user->id");
				}
				else {
					$error_messages['main'] = "Something went wrong. Try again later.";
				}
			}
		}
		// get the user details
		$user = User::find_by_id($_GET['id']);
		
		$page = new AdminPage();
		$page->title = "Edit &lt; {$user->full_name()} &lt; User Settings";
		echo $page->header("Users");
		echo "<div id=\"main_content\">";
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$user->id}&action=edit"; ?>" method="post">
			<h2>Edit User</h2>
			<?php if (isset($error_messages['main'])) { echo "<p class=\"error\">{$error_messages['main']}</p>"; } ?>
			<div class="input">
				<?php if (isset($error_messages['first_name'])) { echo "<p class=\"error\">{$error_messages['first_name']}</p>"; } ?>
				<label for="first_name">First Name</label>
				<input type="text" name="first_name" id="first_name"
					<?php if(isset($_POST['first_name'])) { echo "value=\"{$_POST['first_name']}\""; }
					else { echo "value=\"{$user->first_name}\""; } ?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['last_name'])) { echo "<p class=\"error\">{$error_messages['last_name']}</p>"; } ?>
				<label for="last_name">Last Name</label>
				<input type="text" name="last_name" id="last_name"
					<?php if(isset($_POST['last_name'])) { echo "value=\"{$_POST['last_name']}\""; }
					else { echo "value=\"{$user->last_name}\""; } ?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['email'])) { echo "<p class=\"error\">{$error_messages['email']}</p>"; } ?>
				<label for="email">Email</label>
				<input type="email" name="email" id="email" 
					<?php if(isset($_POST['email'])) { echo "value=\"{$_POST['email']}\""; }
					else { echo "value=\"{$user->email}\""; } ?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['date_of_birth'])) { echo "<p class=\"error\">{$error_messages['date_of_birth']}</p>"; } ?>
				<label for="date_of_birth">Date of Birth</label>
				<input type="date" name="date_of_birth" id="date_of_birth" 
					<?php if(isset($_POST['date_of_birth'])) { echo "value=\"{$_POST['date_of_birth']}\""; }
					else { echo "value=\"{$user->date_of_birth}\""; } ?>
				/>
			</div>
			<div class="input">
				<?php if (isset($error_messages['level'])) { echo "<p class=\"error\">{$error_messages['level']}</p>"; } ?>
				<label for="level">User Group</label>
				<select id="level" name="level">
					<?php
					// get all the levels
					$levels = Level::find_all();
					foreach($levels as $level) {
						echo "<option value={$level->id}";
						if(($level->id == $user->level_id) || ($_POST['level'] == $level->id)) {
							echo " selected=true";
						}
						echo ">{$level->title}</option>";
					}
					?>
				</select>
			</div>
			<div class="input">
				<input type="submit" name="save" value="Save" />
			</div>
		</form>
		<?php
		echo "</div>";
		echo $page->side_bar();
		echo "<div style=\"clear: both;\"></div>";
		echo $page->footer();
	}
	// if the user is to be deleted
	if($_GET['action'] == "delete") {
		// validate user id
		
		$user = User::find_by_id($_GET['id']);
		$deleted = $user->delete();
		if($deleted) {
			header("Location: users.php");
		}
		else {
			header("Location: users.php?id={$_GET['id']}");
		}
	}

}
else if(isset($_GET['id'])) {
	// get the user details
	$user = User::find_by_id($_GET['id']);
	$page = new AdminPage();
	$page->title = $user->full_name()." &lt; Users &lt; Administration";
	$page->add_css("css/users.css");
	echo $page->header("Users");
	echo "<div id=\"main_content\">";
	echo "<div id=\"page_heading\">";
	echo "<h2>{$user->full_name()}</h2>";
	echo "<a href=\"users.php?id={$user->id}&action=edit\">Edit</a>";
	echo " | <a href=\"users.php?id={$user->id}&action=delete\">Delete</a>";
	echo "</div>";
	echo "<div id=\"user_profile\">";
	echo "<div>
		<span class=\"title\">&nbsp;</span>
		<span class=\"bold_value\">{$user->full_name()}</span>
	</div>";
	echo "<div>
		<span class=\"title\">&nbsp;</span>
		<span class=\"value\">{$user->email}</span>
	</div>";
	echo "<div>
		<span class=\"title\">&nbsp;</span>
		<span class=\"value\">{$user->user_group()}</span>
	</div>";
	echo "<div>
		<span class=\"title\">Member Since</span>
		<span class=\"value\">".date("d F Y", $user->register_date)."</span>
	</div>";
	echo "<div>
		<span class=\"title\">Date of Birth</span>
		<span class=\"value\">".date("d F Y", strtotime($user->date_of_birth))."</span>
	</div>";
	echo "<div style=\"clear: both;\"></div>";
	echo "</div>";
	echo "</div>"; // main content

	echo $page->side_bar();
	echo "<div style=\"clear: both;\"></div>";		
	echo $page->footer();
}
else if(isset($_GET['action'])) {
	if($_GET['action'] == "new") {
		// check if the form was submitted
		if(isset($_POST['create'])) {
			$error_messages = array();
			// validate form fields
			if(empty($_POST['first_name'])) {
				$error_messages['first_name'] = "Required field cannot be empty.";
			}
			if(empty($_POST['last_name'])) {
				$error_messages['last_name'] = "Required field cannot be empty.";
			}
			if(empty($_POST['email'])) {
				$error_messages['email'] = "Required field cannot be empty.";
			}
			if(empty($_POST['password'])) {
				$error_messages['password'] = "Required field cannot be empty.";
			}
			if(empty($_POST['date_of_birth'])) {
				$error_messages['date_of_birth'] = "Required field cannot be empty.";
			}
			if($_POST['password'] != $_POST['confirm_password']) {
				$error_messages['confirm_password'] = "Passwords does not match.";
			}
			
			// if there are no errors
			if(empty($error_messages)) {
				// check to see if the email is already taken
				$sql = "SELECT * FROM users WHERE email = \"{$_POST['email']}\";";
				$user = User::find_by_sql($sql);
				if($user) {
					$error_messages['email'] = "Email is already registered.";
				}
				
				if(empty($error_messages)) {
					$user = new User();
					$user->first_name = $_POST['first_name'];
					$user->last_name = $_POST['last_name'];
					$user->email = $_POST['email'];
					$user->password = sha1($_POST['password']);
					$user->date_of_birth = $_POST['date_of_birth'];
					$user->level_id = $_POST['level'];
					$user->register_date = time();
					$saved = $user->save();
					if($saved) {
						header("Location:users.php?id={$user->id}");
					}
					else {
						$error_messages['main'] = "Something went wrong. Please try again later.";
					}
				}
			}
		}
		$page = new AdminPage();
		echo $page->header("Users");
		echo "<div id=\"main_content\">";
		if(!isset($error_messages)) {
			$error_messages = array();
		}
		$form = new FormHelper($error_messages);
		echo $form->start($_SERVER['PHP_SELF']."?action=new");
		echo "<h2>New User</h2>";
		echo $form->text(array("name"=>"first_name", "id"=>"FirstName", "label"=>"First Name"));
		echo $form->text(array("name"=>"last_name", "id"=>"LastName", "label"=>"Last Name"));
		$levels = Level::find_all();
		foreach ($levels as $level) {
			$record[$level->id] = $level->title;
		}
		echo $form->select($record, array("name"=>"level", "id"=>"Level", "label"=>"User Group"));
		echo $form->email(array("name"=>"email", "id"=>"Email", "label"=>"Email"));
		echo $form->password(array("name"=>"password", "id"=>"Password", "label"=>"Password"));
		echo $form->password(array("name"=>"confirm_password", "id"=>"ConfirmPassword", "label"=>"Confirm Password"));
		echo $form->date(array("name"=>"date_of_birth", "id"=>"DateOfBirth", "label"=>"Date of Birth"));
		echo $form->end(array("name"=>"create", "value"=>"Create"));
		
		echo "</div>"; // main_content
		echo $page->side_bar();
		echo "<div style=\"clear: both;\"></div>";
		echo $page->footer();
	}
}
else {
	$page = new AdminPage();
	$page->title = "User Settings &lt; Administration";
	$page->add_css("css/users.css");
	echo $page->header("Users");
	echo "<div id=\"main_content\">";
	echo "<div id=\"page_heading\">";
	echo "<h2>Users</h2>";
	echo "<a href=\"users.php?action=new\">Create new user</a>";
	echo "</div>";

	$users = User::find_all();
	$odd_row = true;
	echo "<div>
			<div id=\"users_header\">
				<span>Name</span>
			</div>";
	foreach($users as $user) {
		if($odd_row) {
			$odd_row = false;
			echo "<div class=\"users_row_odd\">";
		}
		else {
			$odd_row = true;
			echo "<div class=\"users_row_even\">";
		}
		echo "<span><a href=\"users.php?id={$user->id}\">{$user->full_name()}</a></span>
				</div>";
	}
	echo "</div>";
	echo "</div>"; // main content
	// echo side panel
	echo $page->side_bar();
	echo "<div style=\"clear: both;\"></div>";
	echo $page->footer();
}
?>