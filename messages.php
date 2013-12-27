<?php
session_start();

require_once("lib/Page.php");

// if the user is not logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}

$page = new Page();
$page->title = "Messages";
echo $page->header("Messages");
echo $page->breadcrumb(array("Home"=>"index.php"));
echo "<h2>Messages</h2>";
if(isset($_SESSION['logged_in'])) {
	echo "<p><a href=\"new_message.php\" class=\"btn btn-default\">New Message</a></p>";
}
echo $page->footer();
?>