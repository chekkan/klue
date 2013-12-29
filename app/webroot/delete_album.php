<?php
session_start();

require_once("../lib/Models/Album.php");

// make sure the user is logged in.
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

if(isset($_GET['id'])) {
	// save the id
	$album_id = $_GET['id'];
	
	// make sure the album id is valid
	if(!Album::exists($album_id)) {
		die("Album not found.");
	}
	else {
		// delete the album
		$deleted = Album::delete($album_id);
		if($deleted) {
			header("Location: albums.php");
		}
		else {
			die("Could not delete album. Try again later.");
		}
	}
}

?>