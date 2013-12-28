<?php

require_once(LIB_PATH."Database.php");

class Comment extends MySqlDatabase {

	protected static $table_name = "photo_comments";
	public static $db_fields = array();
	
	public $id;
	public $message;
	public $photo_id;
	public $user_id;
	public $time_posted;
	
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
	
	public static function init() {
		self::$db_fields = self::initDbFields();
	}
	
	private static function initDbFields() {
		global $database;
		$fields = array();
		$sql = "SHOW COLUMNS FROM ".self::$table_name.";";
		$result = $database->query($sql);
		while($row = $database->fetch_assoc($result)) {
			$fields[] = $row['Field'];
		}
		
		return $fields;
	}
	
	private function has_attribute($attribute) {
		// get_object_vars returns an associative array with all attributes 
		// (incl. private ones!) as the keys and their current values as the value
		$object_vars = get_object_vars($this);
		// We dont care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $object_vars);
	}
	
	public static function find_by_sql($sql = "") {
		global $database;
		$result_set = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_assoc($result_set)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

	public static function find_by_photo($id=0) {
		$sql = "Select * FROM ".self::$table_name;
		$sql .= " WHERE photo_id = {$id};";
		return self::find_by_sql($sql);
	}
	
	protected function attributes() {
		global $database;
		$attributes = array();
		foreach(self::$db_fields as $field) {
			if(property_exists($this, $field)) {
				$attributes[$field] = $database->escape_value($this->$field);
			}
		}
		return $attributes;
	}
	
	public function save() {		
		return (isset($this->id)) ? $this->update() : $this->create();
	}
	
	public function create() {
		global $database;
		
		// $this->time_created = date("Y-m-d H:i:s");
		$attributes = $this->attributes();
		
		$sql = "INSERT INTO ".self::$table_name."(";
		// take out the id for insert sql
		$array_keys = array_keys($attributes);
		array_shift($array_keys);
		$sql .= join(", ", $array_keys);
		$sql .= ") VALUES ('";
		// take out the value for id from sql
		$array_values = array_values($attributes);
		array_shift($array_values);
		$sql .= join("', '", $array_values);
		$sql .= "');";
		$database->query($sql);
		if($database->affected_rows() == 1)	{
			$this->id = $database->insert_id();
			return true;
		}
		else { 
			return false;
		}
	}
	
	public function update() {
		global $database;
		$attributes = $this->attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=".$this->id.";";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

}

Comment::init();

?>