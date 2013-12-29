<?php
session_start();

require_once("../lib/Page.php");
require_once("../lib/Models/User.php");
require_once("../lib/Models/Level.php");

if(isset($_GET['id'])) {
	// validate the id
	
	// get the user details
	$user = User::find_by_id($_GET['id']);
	// get the level details
	$level = Level::find_by_id($user->level_id);
	
	$page = new Page();
	$page->title = $user->full_name() ." &lt; Profiles";
	echo $page->header("Profile");
	echo $page->breadcrumb(array("Home"=>"index.php", "Profiles"=>"profiles.php"));
	// check if the user profile belongs to the logged in user
	if(isset($_SESSION['logged_in']) && ($_SESSION['user_id'] == $_GET['id'])) {
		echo "<p><a href=\"edit_profile.php\" class=\"btn btn-default\">Edit Profile</a></p>";
	}
	echo "<h2>{$user->full_name()}</h2>";
	echo "<dl>";
	echo "<dd class=\"label label-primary\">{$level->title}</dd>";
	echo "<dt>Date of Birth</dt><dd>{$user->date_of_birth}</dd>";
	echo "<dt>Email<dt><dd>{$user->email}</dd>";
	echo "<dt>Member since</dt><dd>".date("d F Y", $user->register_date)."</dd>";
	echo "</dl>";
	
	echo $page->footer();
}
else {

	$page = new Page();
	$page->title = "Profiles";
	echo $page->header("Profile");
	echo $page->breadcrumb(array("Home"=>"index.php"));
	// get all the users
	$users = User::find_all();
	
	if(empty($users)) {
		echo "<p>No users found.</p>";
	}
	
	foreach ($users as $user) {
		echo "<p><a href=\"profiles.php?id={$user->id}\">{$user->full_name()}</a></p>";
	}
	
	echo $page->footer();
}
?>