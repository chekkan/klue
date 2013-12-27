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
	
	public function header($currentItem) {
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
			<link rel=\"stylesheet\" href=\"styles/bootstrap.css\" type=\"text/css\" media=\"screen\" title=\"Bootstrap\" charset=\"utf-8\" />
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
				<div class=\"container\">
					<div class=\"row\">
						<header>
							<h1>{$this->heading}</h1>
						</header>";
		$return .= $this->nav_bar($currentItem);
		
		return $return;
	}
	
	public function footer() {
		$return = "<footer>
				<p>Powered by Klue Version 0.1&alpha; from Chekkan &copy; 2013</p>
			</footer>
			</div> <!-- div.row -->
			</div> <!-- div.container -->
		</body>
		</html>";
		
		return $return;
	}
	
	public function nav_bar($currentItem) {
		
		$navItems = array(
			"Home" => "index.php",
			"Events" => "events.php",
			"News" => "news.php", 
			"Gallery" => "albums.php");
		// if user is logged in
		if (isset($_SESSION['logged_in'])) {
			$navItems["Messages"] = "messages.php";
			$navItems["Profile"] = "profiles.php?id={$_SESSION['user_id']}";
			// check if the user is an admin
			if ($_SESSION['user_level_id'] == 1) {
				$navItems["Administrator"] = "admin/";
			}
			$navItems["Logout"] = "logout.php";
		} else {
			$navItems["Login"] = "login.php";
			$navItems["Register"] = "register.php";
		}
		$return = "<nav class=\"navbar navbar-default\" role=\"navigation\">
					<ul class=\"nav navbar-nav\">";
		foreach ($navItems as $title => $url)
		{
			$return .= "<li";
			if ($title == $currentItem) {
				$return .= " class=\"active\"";
			}
			$return .= "><a href=\"{$url}\">{$title}</a></li>";
		}
					
		$return .= "</ul></nav>";
		return $return;
	}
	
	public function breadcrumb($path) {
		$return = "<ol class=\"breadcrumb\">";
		foreach($path as $key => $value)
		{
			$return .= "<li><a href=\"{$value}\">{$key}</a></li>";
		}
		$return .= "</li></ol>";
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