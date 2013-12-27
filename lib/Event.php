<?php

require_once("Database.php");

class Event {

	protected static $table_name = "events";
	protected static $db_fields = array();
	
	public $id;
	public $title;
	public $venue;
	public $date;
	public $description;
	public $time_created;
	public $user_id;
	public $draft;
	
	public static function exists($id) {
		$database = new MySqlDatabase();
		$sql = "SELECT id FROM events WHERE id = {$id};";
		$result = $database->query($sql);
		if($database->num_rows($result) == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function findById($id) {
		$database = new MySqlDatabase();
		$sql = "SELECT * FROM events
				WHERE id = {$id};";
		$result = $database->query($sql);
		if($database->num_rows($result) == 0) {
			return false;
		}
		else {
			$row = $database->fetch_assoc($result);
			$event = new Event();
			$event->id = $row['id'];
			$event->title = $row['title'];
			$event->venue = $row['venue'];
			$event->date = $row['date'];
			$event->description = $row['description'];
			$event->time_created = $row['time_created'];
			$event->user_id = $row['user_id'];
			$event->draft = $row['draft'];
			return $event;
		}
	}
	
	public static function findAll() {
		$database = new MySqlDatabase();
		$sql = "SELECT * FROM events;";
		$result = $database->query($sql);
		if($database->num_rows($result) == 0) {
			return false;
		}
		else {
			$events = array();
			while ($row = $database->fetch_assoc($result)) {
				$event = new Event();
				$event->id = $row['id'];
				$event->title = $row['title'];
				$event->venue = $row['venue'];
				$event->date = $row['date'];
				$event->description = $row['description'];
				$event->time_created = $row['time_created'];
				$event->user_id = $row['user_id'];
				$event->draft = $row['draft'];
				array_push($events, $event);
			}
			return $events;
		}
	}
	
	public function create() {
		$database = new MySqlDatabase();
		$this->time_created = time();
		$sql = "INSERT INTO events(title, venue, date, description, user_id, draft, time_created)
				VALUES(\"{$this->title}\", \"{$this->venue}\", \"{$this->date}\",
					 	\"{$this->description}\", {$this->user_id}, {$this->draft},
					 	{$this->time_created});";
		$result = $database->query($sql);
		if($database->affected_rows() == 1) {
			$this->id = $database->insert_id();
			return true;
		}
		else {
			return false;
		}
	}
	
	public function update() {
		$database = new MySqlDatabase();
		$sql = "UPDATE events
				SET title=\"{$this->title}\", 
					venue=\"{$this->venue}\", 
					date=\"{$this->date}\", 
					description=\"{$this->description}\", 
					draft={$this->draft}
				WHERE id={$this->id};";
		$result = $database->query($sql);
		if($database->affected_rows() == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	public static function delete($id) {
		$database = new MySqlDatabase();
		$sql = "DELETE FROM events WHERE id = {$id};";
		$result = $database->query($sql);
		if($database->affected_rows() == 1) {
			return true;
		}
		else {
			return false;
		}
	}
}


?>