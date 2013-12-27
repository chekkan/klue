<?php
session_start();

require_once("lib/Page.php");
require_once("lib/Event.php");
require_once("lib/User.php");
require_once("lib/EventComment.php");
require_once("lib/FormHelper.php");

$error_messages = array();

if(isset($_GET['id'])) {
	// save the id into its variable
	$event_id = $_GET['id'];
	// check to see if the id is a valid number
	
	// check to see if the event exists
	if(!Event::exists($event_id)) {
		die("The event you are looking for doesn't exists.");
	}
	else {
		$event = Event::findById($event_id);
	}
	// if the comment was submitted
	if(isset($_POST['comment'])) {
		// make sure the user is logged in
		if(!isset($_SESSION['logged_in'])) {
			header("Location: login.php");
		}
		$error_messages = array();
		// validate the message
		if(empty($_POST['message'])) {
			$error_messages['message'] = "Required field and cannot be left empty.";
		}
		
		if(empty($error_messages)) {
			$comment = new EventComment();
			$comment->message = $_POST['message'];
			$comment->event_id = $event_id;
			$comment->user_id = $_SESSION['user_id'];
			$comment->time_posted = date("Y-m-d H:i:s");
			$saved = $comment->save();
			if(!$saved) {
				$error_messages['main'] = "Something went wrong. Try again later.";
			}
		}
	}
	$page = new Page();
	$page->title = $event->title . " &lt; Events";
	$page->heading = "Craften";
	echo $page->header("Events");
	echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php"));
	// if the user is logged in, show link to edit the event
	if(isset($_SESSION['logged_in'])) {
		echo "<p><a href=\"edit_event.php?id={$event->id}\" class=\"btn btn-default\">Edit</a>
		<a href=\"delete_event.php?id={$event->id}\" class=\"btn btn-default\">Delete</a>
		</p>";
	}
	echo "<h2>{$event->title}</h2>";
	echo "<dl>";
	echo "<dt>Date</dt><dd>".date('d F Y', strtotime($event->date))."</dd>";
	echo "<dt>Venue</dt><dd>{$event->venue}</dd>";
	echo "<dt>Description</dt><dd>{$event->description}</dd>";
	echo "</dl>";
	// comments
	$comments = EventComment::find_by_event($event->id);
	echo "<section>
			<h2>Comments</h2>";
	
	$form = (isset($error_messages)) ? new FormHelper($error_messages) : new FormHelper();
	echo $form->start($_SERVER['PHP_SELF']."?id={$event->id}");
	echo $form->textarea(array("label" => "Comment", "name" => "message", "id" => "message"));
	echo $form->end("Comment");

	if(empty($comments)) {
		echo "<p>No comments.</p>";
	}
	else {
		foreach ($comments as $comment) {
			// get user details
			$comment_user = User::find_by_id($comment->user_id);
			echo "<article class=\"well\">
					<header>
						<div class=\"col-sm-6\">
							<a href=\"profiles.php?id={$comment_user->id}\">{$comment_user->full_name()}</a>
						</div>
						<div class=\"col-sm-6 text-right\">{$comment->time_posted}</div>
					</header>
					<p>{$comment->message}</p>
				</article>";
		}
	}
	echo "</section>";
	
	echo $page->footer();
}
else {
	// show all the events
	$page = new Page();
	$page->title = "Events";
	$page->heading = "Craften";
	$page->add_css("styles/events.css");
	echo $page->header("Events");
	echo $page->breadcrumb(array("Home"=>"index.php"));
	echo "<h2>Events</h2>";
	
	// if the user is logged in
	if(isset($_SESSION['logged_in'])) {
		echo "<p><a class=\"btn btn-default\" href=\"create_event.php\">Create Event</a></p>";
	}
	$events = Event::findAll();
	if(!$events) {
		echo "<p>No events to be displayed.</p>";
	} else {
		echo "<ul id=\"events\">";
		foreach ($events as $event) {
			echo "<li>";
			echo "<span class=\"date_container\">";
			echo "<span class=\"date\">".date("d", strtotime($event->date))."</span> ";
			echo "<span class=\"month\">".date("M", strtotime($event->date))."</span> ";
			echo "<span class=\"year\">".date("Y", strtotime($event->date))."</span>";
			echo "</span> ";
			echo "<span class=\"event_container\">";
			echo "<span class=\"title\">
					<a href=\"events.php?id={$event->id}\">{$event->title}</a>
				</span> ";
			echo "<span class=\"venue\">{$event->venue}</span>";
			echo "</span>";
			echo "</li>";
		}
		echo "</ul>";
	}
	echo $page->footer();
}
?>