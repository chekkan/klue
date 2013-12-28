<?php

require_once("Database.php");

//TODO: Class should be name Model
class Table {

	protected static $table_name;
	protected static $db_fields = array();

	public static function init() {
		self::$db_fields = self::initDbFields();
	}
	
	protected static function initDbFields() {
		global $database;
		$fields = array();
		$sql = "SHOW COLUMNS FROM ".static::$table_name.";";
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
		// We don't care about the value, we just want to know if the key exists
		// Will return true or false
		return array_key_exists($attribute, $object_vars);
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
	
	private static function instantiate($record) {
		// could check that $record exists and is an array
		$object = new static;
		
		foreach($record as $attribute=>$value) {
			if($object->has_attribute($attribute)) {
				$object->$attribute = $value;
			}
		}
		return $object;
	}

    /**
     * @param string $sql
     * @return array
     */
    public static function find_by_sql($sql="") {
		global $database;
		$result = $database->query($sql);
		$object_array = array();
		while($row = $database->fetch_assoc($result)) {
			$object_array[] = self::instantiate($row);
		}
		return $object_array;
	}

    /**
     * @param int $id
     * @return bool|Table
     */
    public static function find_by_id($id=0) {
		global $database;
		$sql = "SELECT * FROM ".static::$table_name;
		$sql .= " WHERE id={$id} LIMIT 1;";
		$result_array = self::find_by_sql($sql);
		return !empty($result_array) ? array_shift($result_array) : false;
	}

	public static function find_all() {
		$sql = "SELECT * FROM ".static::$table_name.";";
		return self::find_by_sql($sql);
	}

    public static function exists($id) {
        global $database;
        $sql = "SELECT id FROM ".static::$table_name." WHERE id = {$id};";
        $result = $database->query($sql);
        return ($database->num_rows($result) == 1) ? true : false;
    }

	public function save() {		
		return (isset($this->id)) ? $this->update() : $this->create();
	}
	
	public function create() {
		global $database;
		
		$attributes = $this->attributes();
		
		$sql = "INSERT INTO ".static::$table_name."(";
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
		} else {
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
		$sql = "UPDATE ".static::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=".$this->id.";";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

	public function delete() {
		global $database;
		$sql = "DELETE FROM ".static::$table_name;
		$sql .= " WHERE id = {$this->id}";
		$sql .= " LIMIT 1;";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
}

?>