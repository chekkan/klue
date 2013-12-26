<?php
session_start();

require_once("lib/Album.php");
require_once("lib/Page.php");

//check to see if the user is logged in to see this page
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}
// check if there is a id passed
if(!isset($_GET['id'])) {
	$page = new Page();
	$page->end("Album not specified.");
}
else {
	// store the id
	$album_id = $_GET['id'];
	// validate $album_id
	if(!Album::exists($album_id)) {
		$page = new Page();
		$page->end("Album could not be found.");
	}
	else {
		// check to see if the form was submitted
		if(isset($_POST['save'])) {
			// variable to store errors
			$error_messages = array();
			// store the fields
			$title = $_POST['title'];
			$location = $_POST['location'];
			$description = $_POST['description'];
			$date_taken = $_POST['date_taken'];
			$user_id = $_SESSION['user_id'];
			// validate the fields
			if(empty($title)) {
				$error_messages['title'] = "Required field, cannot be empty.";
			}
			// if there are no errors
			if(empty($error_messages)) {
				// update database with new values
				$album = new Album();
				$album->id = $album_id;
				$album->title = $title;
				$album->location = $location;
				$album->description = $description;
				$album->user_id = $user_id;
				$album->date_taken = $date_taken;
				$updated = $album->update();
				if($updated) {
					header("Location: albums.php?id={$album->id}");
				}
				else {
					$error_messages['main'] = "Album was not updated. Try again later.";
				}
			}
		}
		// get the album details
		$album = Album::find_by_id($album_id);
		$page = new Page();
		$page->title = "Edit &lt; Album &lt; Craften";
		echo $page->header();
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$album_id}"; ?>" method="post">
			<h2>Edit Album</h2>
			<?php if (isset($error_messages['main'])) {
				echo "<p class=\"error\">{$error_messages['main']}</p>";
			}?>
			<div class="input">
				<?php if (isset($error_messages['title'])) {
					echo "<p class=\"error\">{$error_messages['title']}</p>";
				}?>
				<label for="title">Title</label>
				<input type="text" name="title" id="title"
					<?php if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } 
					else { echo "value=\"{$album->title}\""; } ?>
				/>
			</div>
			<div class="input">
				<label for="location">Location</label>
				<input type="text" name="location" id="location"
					<?php if (isset($_POST['location'])) { echo "value=\"{$_POST['location']}\""; }
					else { echo "value=\"{$album->location}\""; } ?>
				/>
			</div>
			<div class="input">
				<label for="description">Description</label>
				<textarea name="description" id="description"><?php
					if (isset($_POST['description'])) { echo $_POST['description']; }
					else { echo $album->description; }
				?></textarea>
			</div>
			<div class="input">
				<label for="date_taken">Date</label>
				<input type="date" name="date_taken" id="date_taken"
					<?php if (isset($_POST['date_taken'])) { echo "value=\"{$_POST['date_taken']}\""; } 
					else { echo "value=\"{$album->date_taken}\""; } ?>
				/>
			</div>
			<div class="input">
				<input type="submit" name="save" value="Save" />
			</div>
		</form>
		<?php
		echo $page->footer();
	}
}

?>