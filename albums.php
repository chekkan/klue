<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Album.php");
require_once("lib/Photograph.php");

if(isset($_GET['id'])) {
	// store the variable
	$album_id = $_GET['id'];
	//validate the id
	if(!Album::exists($album_id)) {
		$page = new Page();
		$page->end("Album does not exist.");
	}
	else {
		// get the album details
		$album = Album::find_by_id($album_id);
		$page = new Page();
		$page->title = $album->title." &lt; Gallery";
		$page->add_css("styles/albums.css");
		echo $page->header("Gallery");
		echo $page->breadcrumb(array("Home"=>"index.php", "Gallery"=>"albums.php"));
		echo "<h2>{$album->title}</h2>";
		// check if the user is logged in
		if(isset($_SESSION['logged_in'])) {
			echo "<p>
				<a href=\"add_pictures.php?id={$album->id}\" class=\"btn btn-primary\">Upload Pictures</a>
				<a href=\"edit_album.php?id={$album->id}\" class=\"btn btn-warning\">Edit Album</a>
				<a href=\"delete_album.php?id={$album->id}\" class=\"btn btn-danger\">Delete Album</a>
			</p>";
		}
		
		$photos = Photograph::find_by_album($album->id);
		if(empty($photos)) {
			echo "<p>No photos to display.</p>";
		}
		else {
			echo "<ul id=\"showcase\">";
			foreach($photos as $photo) {
				echo "<li><a href='photos.php?id={$photo->id}'><img src=\"{$photo->image_path()}\" width=\"150\" /></a></li>";
			}
			echo "</ul>";
		}
		echo $page->footer();
	}
}
else {

	$page = new Page();
	$page->title = "Gallery";
	echo $page->header("Gallery");
	echo $page->breadcrumb(array("Home"=>"index.php"));

	echo "<h2>Gallery</h2>";

	// if the user is logged in
	if(isset($_SESSION['logged_in'])) {
		echo "<p><a href=\"create_album.php\" class=\"btn btn-default\">New Album</a></p>";
	}

	// get all the albums
	$albums = Album::findAll();
	if(empty($albums)) {
		echo "<p>No albums to display.</p>";
	}
	foreach ($albums as $album) {
		echo "<p><a href=\"albums.php?id={$album->id}\">{$album->title}</a></p>";
	}

	echo $page->footer();
}
?>