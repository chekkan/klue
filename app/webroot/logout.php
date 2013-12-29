<?php

session_start();

// check to see if the user is not logged in
if(!isset($_SESSION['logged_in'])) {
	header("Location: login.php");
}
else {
	if(!$_SESSION['logged_in']) {
		header("Location: login.php");
	}
	else
	{
		// logout user
		unset($_SESSION['logged_in']);
		unset($_SESSION['user_id']);
		unset($_SESSION['user_level_id']);
		header("Location: login.php");
	}
}

?>