<?php

session_start();

require_once("../lib/Models/Level.php");
require_once("../lib/AdminPage.php");
require_once("../lib/Models/User.php");
require_once("../lib/FormHelper.php");

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
	// get the level details
	$level = Level::find_by_id($_GET['id']);
	
	if(isset($_POST['save'])) {
		$error_messages = array();
		// validate the form fields
		if(empty($_POST['title'])) {
			$error_messages['title'] = "Required field cannot be empty.";
		}
		
		if(empty($error_messages)) {
			$level->title = $_POST['title'];
			$level->permissions = $_POST['gallery'];
			$level->permissions .= $_POST['news'];
			$level->permissions .= $_POST['events'];
			$level->permissions .= $_POST['comments'];
			$saved = $level->save();
			if($saved) {
				header("Location: level_settings.php");
			}
			else {
				$error_messages['main'] = "Something went wrong. Try again later.";
			}
		}
	}

	$page = new AdminPage();
	$page->title = "Edit &lt; Level Settings &lt; Administration";
	echo $page->header("Users");
	echo "<div id=\"main_content\">";
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']."?id={$_GET['id']}&action=edit"; ?>" method="post">
		<h2>Edit Level</h2>
		<?php if(isset($error_messages['main'])) { echo "<p class=\"error\">{$error_messages['main']}</p>"; } ?>
		<div class="input">
			<?php if(isset($error_messages['title'])) { echo "p class=\"error\">{$error_messages['title']}</p>"; } ?>
			<label for="title">Title</label>
			<input type="text" name="title" id="title"
				<?php if(isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; }
				else { echo "value=\"{$level->title}\""; } ?>
			/>
		</div>
		<div class="input">
			<label for="gallery">Gallery</label>
			<select name="gallery" id="gallery">
				<option value="0" 
					<?php if($level->get_permission("Gallery") == "0") { echo "selected=true"; } ?>
				>None</option>
				<option value="1"
					<?php if($level->get_permission("Gallery") == "1") { echo "selected=true"; } ?>
				>View Only</option>
				<option value="2"
					<?php if($level->get_permission("Gallery") == "2") { echo "selected=true"; } ?>
				>View & Create</option>
				<option value="3"
					<?php if($level->get_permission("Gallery") == "3") { echo "selected=true"; } ?>
				>View, Create & Edit</option>
				<option value="4"
					<?php if($level->get_permission("Gallery") == "4") { echo "selected=true"; } ?>
				>All</option>
			</select>
		</div>
		<div class="input">
			<label for="news">News</label>
			<select name="news" id="news">
				<option value="0" 
					<?php if($level->get_permission("News") == "0") { echo "selected=true"; } ?>
				>None</option>
				<option value="1"
					<?php if($level->get_permission("News") == "1") { echo "selected=true"; } ?>
				>View Only</option>
				<option value="2"
					<?php if($level->get_permission("News") == "2") { echo "selected=true"; } ?>
				>View & Create</option>
				<option value="3"
					<?php if($level->get_permission("News") == "3") { echo "selected=true"; } ?>
				>View, Create & Edit</option>
				<option value="4"
					<?php if($level->get_permission("News") == "4") { echo "selected=true"; } ?>
				>All</option>
			</select>
		</div>
		<div class="input">
			<label for="events">Events</label>
			<select name="events" id="events">
				<option value="0" 
					<?php if($level->get_permission("Events") == "0") { echo "selected=true"; } ?>
				>None</option>
				<option value="1"
					<?php if($level->get_permission("Events") == "1") { echo "selected=true"; } ?>
				>View Only</option>
				<option value="2"
					<?php if($level->get_permission("Events") == "2") { echo "selected=true"; } ?>
				>View & Create</option>
				<option value="3"
					<?php if($level->get_permission("Events") == "3") { echo "selected=true"; } ?>
				>View, Create & Edit</option>
				<option value="4"
					<?php if($level->get_permission("Events") == "4") { echo "selected=true"; } ?>
				>All</option>
			</select>
		</div>
		<div class="input">
			<label for="comments">Comments</label>
			<select name="comments" id="comments">
				<option value="0" 
					<?php if($level->get_permission("Comments") == "0") { echo "selected=true"; } ?>
				>None</option>
				<option value="1"
					<?php if($level->get_permission("Comments") == "1") { echo "selected=true"; } ?>
				>View Only</option>
				<option value="2"
					<?php if($level->get_permission("Comments") == "2") { echo "selected=true"; } ?>
				>View & Create</option>
				<option value="3"
					<?php if($level->get_permission("Comments") == "3") { echo "selected=true"; } ?>
				>View, Create & Edit</option>
				<option value="4"
					<?php if($level->get_permission("Comments") == "4") { echo "selected=true"; } ?>
				>All</option>
			</select>
		</div>
		<div class="input">
			<input type="submit" name="save" value="Save" />
		</div>
	</form>
	<?php
	echo "</div>"; // main_content
	echo $page->side_bar();
	echo "<div style=\"clear: both;\"></div>";
	echo $page->footer();
}
else if(isset($_GET['id'])) {
	// if the admin wants to view details about the user group
	
	// get the level details
	$level = Level::find_by_id($_GET['id']);
	$page = new AdminPage();
	$page->title = $level->title." &lt; User Groups &lt; Administration";
	$page->add_css("css/levels.css");
	echo $page->header("Users");
	echo "<div id=\"main_content\">";
	echo "<div id=\"page_heading\">";
	echo "<h2>{$level->title}</h2>";
	echo "<a href=\"{$_SERVER['PHP_SELF']}?id={$level->id}&action=edit\">Edit</a>";
	echo "</div>"; // page heading
	
	echo "<div id=\"level_details\">";
	echo "<div>
			<span class=\"title\">&nbsp;</span>
			<span class=\"value\">{$level->title}</span>
		</div>
		<div>
				<span class=\"title\">Permission</span>
				<span class=\"value\">
					<table>
						<tr>
							<td></td>
							<td>View</td>
							<td>Create</td>
							<td>Edit</td>
							<td>Delete</td>
						</tr>";
						// get the permission for gallery
						$permission = $level->get_permission("Gallery");
						echo "
						<tr>
							<td>Gallery</td>";
							for($i = 0; $i < 4; $i++){
								if($permission > 0) {
									echo "<td>1</td>";
								}
								else {
									echo "<td>0</td>";
								}
								$permission--;
							}	
						echo "
						</tr>";
						// get the permission for News
						$permission = $level->get_permission("News");
						echo "
						<tr>
							<td>News</td>";
							for($i = 0; $i < 4; $i++){
								if($permission > 0) {
									echo "<td>1</td>";
								}
								else {
									echo "<td>0</td>";
								}
								$permission--;
							}
						echo "
						</tr>";
						// get the permission for News
						$permission = $level->get_permission("News");
						echo "
						<tr>
							<td>News</td>";
							for($i = 0; $i < 4; $i++){
								if($permission > 0) {
									echo "<td>1</td>";
								}
								else {
									echo "<td>0</td>";
								}
								$permission--;
							}
						echo "
						</tr>";
						// get the permission for Events
						$permission = $level->get_permission("Events");
						echo "
						<tr>
							<td>Events</td>";
							for($i = 0; $i < 4; $i++){
								if($permission > 0) {
									echo "<td>1</td>";
								}
								else {
									echo "<td>0</td>";
								}
								$permission--;
							}
						echo "
						</tr>";
						// get the permission for Comments
						$permission = $level->get_permission("Comments");
						echo "
						<tr>
							<td>Comments</td>";
							for($i = 0; $i < 4; $i++){
								if($permission > 0) {
									echo "<td>1</td>";
								}
								else {
									echo "<td>0</td>";
								}
								$permission--;
							}
						echo "
						</tr>
					</table>
				</span>
			</div>";
	echo "<div style=\"clear:both;\"></div>";
	echo "</div>"; // level_details
	echo "</div>"; // main content
	echo $page->side_bar();
	echo "<div style=\"clear:both;\"></div>";
	echo $page->footer();
}
else if (isset($_GET['action'])) {
	// if the user wants to create a new level
	if($_GET['action'] == "new") {
		// if the form was already posted
		if(isset($_POST['create'])) {
			// validate form fields
			$error_messages = array();
			if(empty($_POST['title'])) {
				$error_messages['title'] = "Required field cannot be empty.";
			}
			
			// if there were no errors
			if(empty($error_messages)) {
				$level = new Level();
				$level->title = $_POST['title'];
				$level->permissions = $_POST['gallery'];
				$level->permissions .= $_POST['news'];
				$level->permissions .= $_POST['events'];
				$level->permissions .= $_POST['comments'];
				$saved = $level->save();
				if($saved) {
					header("Location: levels.php?id={$level->id}");
				}
				else {
					$error_messages['main'] = "Something went wrong. Try again later.";
				}
			}
		}
		$page = new AdminPage();
		$page->title = "New &lt; User Group &lt; Administration";
		echo $page->header("Users");
		echo "<div id=\"main_content\">";
		if(!isset($error_messages)) {
			$error_messages = array();
		}
		$form = new FormHelper($error_messages);
		echo $form->start(array("action"=>$_SERVER['PHP_SELF']."?action=new"));
		echo "<h2>New User Group</h2>";
		echo $form->text(array("name"=>"title", "label"=>"Title", "id"=>"Title"));
		echo $form->select(array("0"=>"None", "1"=>"View Only", "2"=>"View & Create", "3"=>"View, Create & Edit", "4"=>"All"), array("name"=>"gallery", "id"=>"Gallery", "label"=>"Gallery"));
		echo $form->select(array("0"=>"None", "1"=>"View Only", "2"=>"View & Create", "3"=>"View, Create & Edit", "4"=>"All"), array("name"=>"news", "id"=>"News", "label"=>"News"));
		echo $form->select(array("0"=>"None", "1"=>"View Only", "2"=>"View & Create", "3"=>"View, Create & Edit", "4"=>"All"), array("name"=>"events", "id"=>"Events", "label"=>"Events"));
		echo $form->select(array("0"=>"None", "1"=>"View Only", "2"=>"View & Create", "3"=>"View, Create & Edit", "4"=>"All"), array("name"=>"comments", "id"=>"Comments", "label"=>"Comments"));
		echo $form->end(array("name"=>"create", "value"=>"Create"));
		echo "</div>"; // main_content
		echo $page->side_bar();
		echo "<div style=\"clear: both;\"></div>";
		echo $page->footer();
	}
}
else {
	$page = new AdminPage();
	$page->title="Level Settings &lt; Administration";
	echo $page->header("Users");
	echo "<div id=\"main_content\">";
	echo "<div id=\"page_heading\">";
	echo "<h2>User Groups</h2>";
	echo "<a href=\"{$_SERVER['PHP_SELF']}?action=new\">Create new group</a>";
	echo "</div>";
	$levels = Level::find_all();
	foreach ($levels as $level) {
		echo "<p>
				
				<a href=\"{$_SERVER['PHP_SELF']}?id={$level->id}\">{$level->title}</a>
			</p>";
	}
	echo "</div>";// main content
	// echo side panel
	echo $page->side_bar();
	echo "<div style=\"clear: both;\"></div>";
	echo $page->footer();
}
?>