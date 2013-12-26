<?php
session_start();

require_once("../lib/AdminPage.php");

// check if the user is logged in
if(isset($_SESSION['logged_in'])) {
	// and that he is an administrator
	if(!User::is_admin($_SESSION['user_id'])) {
		header("Location: ../login.php");
	}
}
else {
	header("Location: ../login.php");
}

$page = new AdminPage();
$page->title = "Dashboard &lt; Administration";
echo $page->header();

echo $page->footer();
?>