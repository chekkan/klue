<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Models/Album.php");
require_once("lib/Models/Photograph.php");

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
		$page->add_css("css/add_pictures.css");
		$page->add_script("scripts/add_pictures.js");
		echo $page->header("Gallery");
		echo $page->breadcrumb(array("Home"=>"index.php", "Gallery"=>"albums.php", 
								$album->title=>"albums.php?id={$album->id}"));
		echo "<h2>{$album->title}</h2>"; ?>
		<form action="<?php echo $_SERVER['PHP_SELF'] . "?id={$album->id}"; ?>" method="post" enctype="multipart/form" class="form-horizontal" role="form">
			<h3>Upload Pictures</h3>
			<?php if (isset($message)) { echo "<p>{$message}</p>"; } ?>
			<input type="hidden" name="MAX_FILE_SIZE" value="4000000" />
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="file" name="file_upload" id="FileUpload" />
				</div>
			</div>
			<div class="form-group">
				<label for="caption" class="col-sm-2 control-label">Caption</label>
				<div class="col-sm-10">
					<input type="text" name="caption" id="caption" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label for="location" class="col-sm-2 control-label">Location</label>
				<div class="col-sm-10">
					<input type="text" name="location" id="location" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<label for="date_taken" class="col-sm-2 control-label">Date</label>
				<div class="col-sm-10">
					<input type="date" name="date_taken" id="date_taken" class="form-control" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" name="upload" value="Upload" class="btn btn-default" />
				</div>
			</div>
		</form>
		<?php
		echo $page->footer();
	}
}

?>