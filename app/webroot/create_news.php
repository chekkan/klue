<?php
session_start();

require_once("../lib/Page.php");
require_once("../lib/Models/News.php");
require_once("../../lib/FormHelper.php");

// if the user is not logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

if(isset($_POST['post'])) {
	// variable to store error messages
	$error_messages = array();
	// store fields into appropriate variables
	$title = $_POST['title'];
	$message = $_POST['message'];
	$user_id = $_SESSION['user_id'];
	isset($_POST['draft']) ? $draft = 1 : $draft = 0;

	// validate the form
	if(empty($title)) {
		$error_messages['title'] = "Required field, cannot be empty.";
	}
	if(empty($message)) {
		$error_messages['message'] = "Required field, cannot be empty.";
	}
	
	if(empty($error_messages)) {
		// insert news
		$news = new News();
		$news->title = $title;
		$news->message = $message;
		$news->user_id = $user_id;
		$news->draft = $draft;
		$posted = $news->create();
		if($posted) {
			header("Location: news.php?id={$news->id}");
		}
		else {
			$error_messages['main'] = "News was not posted successfully. Please try again later.";
		}
	}
}

$page = new Page();
$page->title = "Create &lt; News &lt; Klue";
$page->heading = "Klue";
echo $page->header("News");
echo $page->breadcrumb(array("Home"=>"index.php", "News"=>"news.php"));

$form = (isset($error_messages)) ? new FormHelper($error_messages) : new FormHelper();
echo $form->start(array("heading" => "Create News", "class" => "form-horizontal"));
echo $form->text(array("name" => "title", "label" => "Title", "placeholder"=>"Title"));
echo $form->textarea(array("label" => "Message", "name" => "message", "id" => "Message", "placeholder" => "Message"));
echo $form->checkbox("");
echo $form->end("Post");

echo $page->footer();
?>