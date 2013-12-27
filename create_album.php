<?php
session_start();

require_once("lib/Album.php");
require_once("lib/Page.php");

// make sure the user is logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

// if the form is submitted
if(isset($_POST['create'])) {
	// variable to store errors
	$error_messages = array();
	// save the variables
	$title = $_POST['title'];
	$location = $_POST['location'];
	$description = $_POST['description'];
	$date_taken = $_POST['date_taken'];
	$user_id = $_SESSION['user_id'];
	
	// validate variables
	if(empty($title)) {
		$error_messages['title'] = "Required field cannot be left empty.";
	}
	
	if(empty($error_messages)) {
		// create the album
		$album = new Album();
		$album->title = $title;
		$album->location = $location;
		$album->description = $description;
		$album->date_taken = $date_taken;
		$album->user_id = $user_id;
		$created = $album->create();
		if($created) {
			header("Location: albums.php?id={$album->id}");
		}
		else {
			$error_messages['main'] = "Album was not created. Try again later.";
		}
	}
}

$page = new Page();
$page->title = "Create &lt; Albums &lt; Craften";
echo $page->header("Gallery");
echo $page->breadcrumb(array("Home"=>"index.php", "Gallery"=>"albums.php"));
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal" role="form">
	<h2>Create Album</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"text-danger\">{$error_messages['main']}</p>";
	}?>
	<div class="form-group">
		<label for="title" class="col-sm-2 control-label">Title</label>
		<div class="col-sm-10">
			<input type="text" name="title" id="title" class="form-control"
				<?php if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } ?>
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
				<?php if (isset($_POST['location'])) { echo "value=\"{$_POST['location']}\""; } ?>
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
				<?php if (isset($_POST['date_taken'])) { echo "value=\"{$_POST['date_taken']}\""; } ?>
			/>
			<?php if (isset($error_messages['date_taken'])) {
				echo "<p class=\"text-danger\">{$error_messages['date_taken']}</p>";
			}?>
		</div>
	</div>
	<div class="form-group">
		<div class="col-sm-offset-2 col-sm-10">
			<input type="submit" name="create" value="Create" class="btn btn-default" />
		</div>
	</div>
</form>
<?php
echo $page->footer();
?>