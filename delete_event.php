<?php
session_start();

require_once("lib/Event.php");

// make sure the user is logged in.
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

if(isset($_GET['id'])) {
	// save the id
	$event_id = $_GET['id'];
	
	// make sure the even id is valid
	if(!Event::exists($event_id)) {
		die("Event not found.");
	}
	else {
		// delete the event
		$deleted = Event::delete($event_id);
		if($deleted) {
			header("Location: events.php");
		}
		else {
			die("Could not delete event. Try again later.");
		}
	}
}

?>