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
		echo $page->header("Gallery");
		?>
		<form action="<?php echo $_SERVER['PHP_SELF']."?id={$album_id}"; ?>" method="post" class="form-horizontal" role="form">
			<h2>Edit Album</h2>
			<?php if (isset($error_messages['main'])) {
				echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
			}?>
			<div class="form-group">
				<label for="title" class="col-sm-2 control-label">Title</label>
				<div class="col-sm-10">
					<input type="text" name="title" id="title" class="form-control"
						<?php if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } 
						else { echo "value=\"{$album->title}\""; } ?>
					/>
					<?php if (isset($error_messages['title'])) {
						echo "<p class=\"text-danger\">{$error_messages['title']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<label for="location" class="col-sm-2 control-label">Location</label>
				<div class="col-sm-10">
					<input type="text" name="location" id="location" class="form-control"
						<?php if (isset($_POST['location'])) { echo "value=\"{$_POST['location']}\""; }
						else { echo "value=\"{$album->location}\""; } ?>
					/>
					<?php if (isset($error_messages['location'])) {
						echo "<p class=\"text-danger\">{$error_messages['location']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<label for="description" class="col-sm-2 control-label">Description</label>
				<div class="col-sm-10">
					<textarea name="description" id="description" class="form-control"><?php
						if (isset($_POST['description'])) { echo $_POST['description']; }
						else { echo $album->description; }
					?></textarea>
					<?php if (isset($error_messages['description'])) {
						echo "<p class=\"text-danger\">{$error_messages['description']}</p>";
					}?>
				</div>
			</div>
			<div class="form-group">
				<label for="date_taken" class="col-sm-2 control-label">Date</label>
				<div class="col-sm-10">
					<input type="date" name="date_taken" id="date_taken" class="form-control"
						<?php if (isset($_POST['date_taken'])) { echo "value=\"{$_POST['date_taken']}\""; } 
						else { echo "value=\"{$album->date_taken}\""; } ?>
					/>
					<?php if (isset($error_messages['date_taken'])) {
						echo "<p class=\"text-danger\">{$error_messages['date_taken']}</p>";
					}?>
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
	}
}

?>