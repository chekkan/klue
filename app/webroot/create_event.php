<?php
session_start();

require_once("../lib/Page.php");
require_once("../lib/Models/Event.php");
require_once("../../lib/FormHelper.php");

// make sure the user is logged in before letting them see this page
if(!isset($_SESSION['logged_in']) && $_SESSION['logged_in'] == false) {
	header("Location: login.php");
}

// if the form is submitted
if(isset($_POST['create'])) {
	// error messages array variable
	$error_messages = array();
	// set all fields to their variables
	$title = $_POST['title'];
	$date = $_POST['date'];
	$venue = $_POST['venue'];
	$description = $_POST['description'];
	isset($_POST['draft']) ? $draft = 1 : $draft = 0;
	// all fields are required except description
	if(empty($title)) {
		$error_messages['title'] = "This field is required and cannot be left empty.";
	}
	if(empty($date)) {
		$error_messages['date'] = "This field is required and cannot be left empty.";
	}
	if(empty($venue)) {
		$error_messages['venue'] = "This field is required and cannot be left empty.";
	}
	
	if(empty($error_messages)) {
		$event = new Event();
		$event->title = $title;
		$event->date = $date;
		$event->venue = $venue;
		$event->description = $description;
		$event->user_id = $_SESSION['user_id'];
		$event->draft = $draft;
		$created = $event->create();
		if($created) {
			header("Location: events.php?id={$event->id}");
		}
		else {
			$error_messages['main'] = "Cannot create the event. Try again later.";
		}
	}
}

$page = new Page();
$page->title = "Create &lt; Event &lt; Klue";
$page->heading = "Klue";
echo $page->header("Events");
echo $page->breadcrumb(array("Home"=>"index.php", "Events"=>"events.php"));

$form = (isset($error_messages)) ? new FormHelper($error_messages) : new FormHelper();
echo $form->start(array("class"=>"form-horizontal", "heading"=>"Create Event"));
echo $form->text(array("name" => "title", "label"=>"Title", "placeholder"=>"Title"));
echo $form->date(array("name"=>"date", "label"=>"Date"));
echo $form->text(array("name"=>"venue", "label"=>"Venue", "placeholder"=>"Venue"));
echo $form->textarea(array("name"=>"description", "label"=>"Description","placeholder"=>"Description"));
echo $form->end("Create");

echo $page->footer();
?>