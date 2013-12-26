<?php

require_once("Settings.php");

class Page {
	
	public $title;
	public $heading;
	protected $stylesheets = array();
	protected $scripts = array();
	
	function __construct() {
		global $settings;
		
		$this->heading = $settings->site_name;
	}
	
	public function header() {
		global $settings;
			
		if(empty($this->heading)) {
			$this->heading = "Craften";
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
			<link rel=\"stylesheet\" href=\"styles/master.css\" type=\"text/css\" media=\"screen\" title=\"Master StyleSheet\" charset=\"utf-8\" />";
		foreach ($this->stylesheets as $link) {
			$return .= "<link rel=\"stylesheet\" href=\"{$link}\" type=\"text/css\" media=\"screen\" charset=\"utf-8\" />";
		}
		foreach ($this->scripts as $link) {
			$return .= "<script src=\"{$link}\" type=\"text/javascript\"></script>";
		}
		$return .= "<script src=\"http://html5demos.com/h5utils.js\"></script>";
		$return .= "
			</head>
			<body>
				<header>
					<h1>{$this->heading}</h1>
				</header>";
		$return .= $this->nav_bar();
		
		return $return;
	}
	
	public function footer() {
		$return = "<footer>
				Powered by Craften Version 0.1 &alpha; from Reloadead &copy; 2011
			</footer>
		</body>
		</html>";
		
		return $return;
	}
	
	public function nav_bar() {
		$return = "<nav>
				<ul>";
		$return .= "<li><a href=\"index.php\">Home</a></li>";
		$return .= "<li><a href=\"events.php\">Events</a></li>";
		$return .= "<li><a href=\"news.php\">News</a></li>";
		$return .= "<li><a href=\"albums.php\">Gallery</a></li>";
		if (isset($_SESSION['logged_in'])) {
			$return .= "<li><a href=\"messages.php\">Messages</a></li>";
			$return .= "<li><a href=\"profiles.php?id={$_SESSION['user_id']}\">Profile</a></li>";
			// check if the user is an admin
			if($_SESSION['user_level_id'] == 1) {
				$return .= "<li><a href=\"admin/\">Administration</a></li>";
			}
			$return .= "<li><a href=\"logout.php\">Logout</a></li>";
		}
		else {
			$return .= "<li><a href=\"login.php\">Login</a></li>";
			$return .= "<li><a href=\"register.php\">Register</a></li>";
		}
					
		$return .= "</ul></nav>";
		return $return;
	}
	
	public function breadcrumb($path) {
		$return = "<div id=\"breadcrumb\">";
		foreach($path as $key => $value)
		{
			$return .= "<a href=\"{$value}\">{$key}</a> &gt; ";
		}
		// remove the last 6 words
		$return = substr($return, 0, -6);
		$return .= "</div>";
		return $return;
	}
	
	public function add_css($link) {
		array_push($this->stylesheets, $link);
	}
	
	public function add_script($link) {
		array_push($this->scripts, $link);
	}
	
	public function end($message) {
		echo $this->header();
		echo "<p>{$message}</p>";
		echo $this->footer();
		die();
	}
	
}

?>