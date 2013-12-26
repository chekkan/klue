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
echo $page->header();
echo $page->breadcrumb(array("Home"=>"index.php", "Gallery"=>"albums.php"));
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<h2>Create Album</h2>
	<?php if (isset($error_messages['main'])) {
		echo "<p class=\"error\">{$error_messages['main']}</p>";
	}?>
	<div class="input">
		<?php if (isset($error_messages['title'])) {
			echo "<p class=\"error\">{$error_messages['title']}</p>";
		}?>
		<label for="title">Title</label>
		<input type="text" name="title" id="title"
			<?php if (isset($_POST['title'])) { echo "value=\"{$_POST['title']}\""; } ?>
		/>
	</div>
	<div class="input">
		<label for="location">Location</label>
		<input type="text" name="location" id="location"
			<?php if (isset($_POST['location'])) { echo "value=\"{$_POST['location']}\""; } ?>
		/>
	</div>
	<div class="input">
		<label for="description">Description</label>
		<textarea name="description" id="description"><?php
			if (isset($_POST['description'])) { echo $_POST['description']; } 
		?></textarea>
	</div>
	<div class="input">
		<label for="date_taken">Date</label>
		<input type="date" name="date_taken" id="date_taken"
			<?php if (isset($_POST['date_taken'])) { echo "value=\"{$_POST['date_taken']}\""; } ?>
		/>
	</div>
	<div class="input">
		<input type="submit" name="create" value="Create" />
	</div>
</form>
<?php
echo $page->footer();
?>