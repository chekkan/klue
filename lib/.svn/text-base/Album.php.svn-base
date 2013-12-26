<?php

require_once("Database.php");

class Album {
	
	protected static $table_name = "albums";
	
	public $id;
	public $title;
	public $location;
	public $description;
	public $user_id;
	public $date_taken;
	public $time_created;
	
	public static function exists($id) {
		$database = new MySqlDatabase();
		$sql = "SELECT id FROM albums WHERE id = {$id};";
		$result = $database->query($sql);
		
		if($database->num_rows($result) == 1) {
			return true;
		}
		else {
			return false;
		}
	}
	
	private static function instantiate($record) {
		// could check that $record exists and is an array
		$object = new self;
		
		foreach($record as $attribute=>$value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}
	
	private function has_attribute($attribute) {
		// get_object_vars returns an associative array with all attributes 
		// (incl. private ones!) as the keys and their current values as the value
		$object_vars = get_object_vars($this);
		// We dont care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $object_vars);
	}
	
	public static function find_by_sql($sql="") {
		global $database;
		$result = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_assoc($result)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}
	
	public static function find_by_id($id=0) {
		global $database;
		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE id={$id} LIMIT 1;";
		$result_array = self::find_by_sql($sql);
		
		return !empty($result_array) ? array_shift($result_array) : false;
	}
	
	public static function findAll() {
		$database = new MySqlDatabase();
		$sql = "SELECT * FROM albums;";
		$result = $database->query($sql);
		$albums = array();
		
		if($database->num_rows($result) > 0) {
			while($row = $database->fetch_assoc($result)) {
				$album = new Album();
				$album->id = $row['id'];
				$album->title = $row['title'];
				$album->location = $row['location'];
				$album->description = $row['description'];
				$album->user_id = $row['user_id'];
				$album->date_taken = $row['date_taken'];
				$album->time_created = $row['time_created'];
				array_push($albums, $album);
			}
		}
		
		return $albums;
	}
	
	public function create() {
		$database = new MySqlDatabase();
		$this->time_created = date("Y-m-d H:i:s");
		$sql = "INSERT INTO albums(title, location, description, user_id, date_taken, time_created)
				VALUES(\"{$this->title}\", \"{$this->location}\", \"{$this->description}\", 
						{$this->user_id}, \"{$this->date_taken}\", \"{$this->time_created}\");";
		$result = $database->query($sql);
		if($database->affected_rows() == 1)	{
			$this->id = $database->insert_id();
			return true;
		}
		else {
			return false;
		}
	}
	
	public function update() {
		$database = new MySqlDatabase();
		$sql = "UPDATE albums
				SET title=\"{$this->title}\", 
					location=\"{$this->location}\", 
					description=\"{$this->description}\",
					user_id={$this->user_id}, 
					date_taken=\"{$this->date_taken}\"
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
		$sql = "DELETE FROM albums WHERE id = {$id};";
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