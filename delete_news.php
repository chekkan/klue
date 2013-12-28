<?php
session_start();

require_once("lib/Models/News.php");

// make sure the user is logged in.
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

if(isset($_GET['id'])) {
	// save the id
	$news_id = $_GET['id'];
	
	// make sure the news id is valid
	if(!News::exists($news_id)) {
		die("News not found.");
	}
	else {
		// delete the news
		$deleted = News::exists($news_id)? delete($news_id) : false;
		if($deleted) {
			header("Location: news.php");
		}
		else {
			die("Could not delete news. Try again later.");
		}
	}
}

?>