<?php

require_once("Page.php");
require_once("Models/User.php");

class AdminPage extends Page {
	
	public function header($currentItem) {
		global $settings;

		if(empty($this->heading)) {
			$this->heading = $settings->site_name;
		}
		if(empty($this->title)) {
			$this->title = $settings->site_name;
		}
		else {
			$this->title .= " &lt; ".$settings->site_name;
		}

		$return = "<!DOCTYPE html>
			<html>
			<head>
			<title>{$this->title}</title>
			<link href='http://fonts.googleapis.com/css?family=Varela' rel='stylesheet' type='text/css'>
			<link href='http://fonts.googleapis.com/css?family=Magra:400,700' rel='stylesheet' type='text/css'>
			<link rel=\"stylesheet\" href=\"css/master.css\" type=\"text/css\" media=\"screen\" title=\"Master StyleSheet\" charset=\"utf-8\" />";
		foreach ($this->stylesheets as $link) {
			$return .= "<link rel=\"stylesheet\" href=\"{$link}\" type=\"text/css\" media=\"screen\" charset=\"utf-8\" />";
		}
		foreach ($this->scripts as $link) {
			$return .= "<script src=\"{$link}\" type=\"text/javascript\"></script>";
		}
		$return .= "
			</head>
			<body>
				<header>
					<span id=\"header_left\">
						<h1>{$this->heading}</h1>
						<span>
							<a href=\"../index.php\">http://localhost/~Harish/craften/index.php
							</a>
						</span>
					</span>
					<span id=\"header_right\">";
		// get the admins name
		if(isset($_SESSION['logged_in'])) {
			$admin = User::find_by_id($_SESSION['user_id']);
			$return .= "<span><a href=\"../profiles.php?id={$admin->id}\">{$admin->full_name()}</a></span>";
			$return .= " <span><a href=\"../logout.php\">Logout</a></span>";
		}
		$return .= "</span>";
		$return .= "</header>";
		$return .= $this->nav_bar($currentItem);
		$return .= "<div id=\"content\">";

		return $return;
	}
	
	public function nav_bar($currentItem) {
		$filename = basename($_SERVER['PHP_SELF']);
		$return = "<nav>
				<ul>";
		if($filename == "index.php") {
			$return .= "<li><a class=\"current\" href=\"index.php\">Dashboard</a></li>";
		}
		else {
			$return .= "<li><a href=\"index.php\">Dashboard</a></li>";
		}
		// if($filename == "site_settings.php") {
		// 			$return .= "<li><a class=\"current\" href=\"site_settings.php\">Site</a></li>";
		// 		}
		// 		else {
		// 			$return .= "<li><a href=\"site_settings.php\">Site</a></li>";
		// 		}
		if(($filename == "users.php") || ($filename == "levels.php")) {
			$return .= "<li><a class=\"current\" href=\"users.php\">Users</a></li>";
		}
		else {
			$return .= "<li><a href=\"users.php\">Users</a></li>";
		}
		if(($filename == "modules.php") || ($filename == "gallery.php")) {
			$return .= "<li><a class=\"current\" href=\"modules.php\">Modules</a></li>";
		}
		else {
			$return .= "<li><a href=\"modules.php\">Modules</a></li>";
		}
		
		$return .= "</ul></nav>";
		return $return;
	}
	
	public function footer() {
		$return = "</div>";
		$return .= "<footer>
				Powered by Craften Version 0.1 &alpha; from Reloadead &copy; 2011
			</footer>
		</body>
		</html>";
		
		return $return;
	}
	
	public function side_bar() {
		if((basename($_SERVER['PHP_SELF']) == "users.php") || (basename($_SERVER['PHP_SELF']) == "levels.php")) {
			return $this->users_side_bar();
		}
		else if((basename($_SERVER['PHP_SELF']) == "modules.php") || (basename($_SERVER['PHP_SELF']) == "gallery.php")) {
			return $this->modules_side_bar();
		}
	}
	
	public function users_side_bar() {
		$return =<<<EOD
	<div id="side_bar">
		<ul>
EOD;
		if(basename($_SERVER['PHP_SELF']) == "users.php") {
			$return .= "<li class=\"current\"><a class=\"current\" href=\"users.php\">All Users</a></li>";
		}
		else {
			$return .= "<li><a href=\"users.php\">All Users</a></li>";
		}
		if(basename($_SERVER['PHP_SELF']) == "levels.php") {
			$return .= "<li class=\"current\"><a class=\"current\" href=\"levels.php\">User Groups</a></li>";
		}
		else {
			$return .= "<li><a href=\"levels.php\">User Groups</a></li>";
		}
		$return .=<<<EOD
		</ul>
	</div>	
EOD;
		return $return;
	}
	
	public function modules_side_bar() {
		$return =<<<EOD
	<div id="side_bar">
		<ul>
EOD;
		if(basename($_SERVER['PHP_SELF']) == "modules.php") {
			$return .= "<li class=\"current\"><a class=\"current\" href=\"modules.php\">General</a></li>";
		}
		else {
			$return .= "<li><a href=\"modules.php\">General</a></li>";
		}
		if(basename($_SERVER['PHP_SELF']) == "gallery.php") {
			$return .= "<li class=\"current\"><a class=\"current\" href=\"gallery.php\">Gallery</a></li>";
		}
		else {
			$return .= "<li><a href=\"gallery.php\">Gallery</a></li>";
		}
		if(basename($_SERVER['PHP_SELF']) == "news.php") {
			$return .= "<li class=\"current\"><a class=\"current\" href=\"news.php\">News</a></li>";
		}
		else {
			$return .= "<li><a href=\"news.php\">News</a></li>";
		}
		if(basename($_SERVER['PHP_SELF']) == "events.php") {
			$return .= "<li class=\"current\"><a class=\"current\" href=\"events.php\">Events</a></li>";
		}
		else {
			$return .= "<li><a href=\"events.php\">Events</a></li>";
		}
		$return .=<<<EOD
		</ul>
	</div>	
EOD;
		return $return;
	}
	
}

?>