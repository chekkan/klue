<?php
session_start();

require_once("lib/Album.php");
require_once("lib/Photograph.php");
require_once("lib/Page.php");

// check if the user is logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

// check if an album id was passed via url
if(!isset($_GET['id'])) {
	$page = new Page();
	$page->end("No album specified.");
}
else {
	// store the id
	$album_id = $_GET['id'];
	// validate the album id
	if(!Album::exists($album_id)) {
		$page = new Page();
		$page->end("Invalid album id given.");
	}
	else {
		// check to see if the upload button was clicked
		if(isset($_POST['upload'])) {
			// process the form data
			$photo = new Photograph();
			$photo->caption = $_POST['caption'];
			$photo->user_id = $_SESSION['user_id'];
			$photo->album_id = $album_id;
			$photo->date_taken = $_POST['date_taken'];
			$photo->location = $_POST['location'];
			$photo->time_uploaded = date("Y-m-d H:i:s");
			$photo->attach_file($_FILES['file_upload']);
						
			if($photo->save()) {
				// Success
				$message = "Photograph uploaded successfully.";
			}
			else {
				// Failure
				$message = join("<br />", $photo->errors);
			}
		}
		// get the album details
		$album = Album::find_by_id($album_id);
		// display the page
		$page = new Page();
		$page->title = "Upload Pictures &lt; {$album->title} &lt; Craften";
		$page->add_css("styles/add_pictures.css");
		$page->add_script("scripts/add_pictures.js");
		echo $page->header();
		echo $page->breadcrumb(array("Home"=>"index.php", "Gallery"=>"albums.php", 
								$album->title=>"albums.php?id={$album->id}"));
		echo "<h2>{$album->title}</h2>"; ?>
		<form action="<?php echo $_SERVER['PHP_SELF'] . "?id={$album->id}"; ?>" method="post" enctype="multipart/form">
			<h3>Upload Pictures</h3>
			<?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
			<input type="hidden" name="MAX_FILE_SIZE" value="4000000" />
			<div class="input">
				<input type="file" name="file_upload" />
			</div>
			<div class="input">
				<label for="caption">Caption</label>
				<input type="text" name="caption" id="caption" />
			</div>
			<div class="input">
				<label for="location">Location</label>
				<input type="text" name="location" id="location" />
			</div>
			<div class="input">
				<label for="date_taken">Date</label>
				<input type="date" name="date_taken" id="date_taken" />
			</div>
			<div class="input">
				<input type="submit" name="upload" value="Upload" />
			</div>
		</form>
		<?php
		echo $page->footer();
	}
}

?>