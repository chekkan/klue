<?php

require_once(LIB_PATH."Table.php");

class Level extends Table {

	protected static $table_name = "levels";
	protected static $db_fields = array();
	
	public $id;
	public $title;
	public $permissions;
	
	public function get_permission($module="") {
		switch($module) {
			case "Gallery":
				return $this->permissions[0];
			break;
			case "News":
				return $this->permissions[1];
			break;
			case "Events":
				return $this->permissions[2];
			break;
			case "Comments":
				return $this->permissions[3];
			break;
			default:
				return false;
			break;
		}
	}
	
	public function update() {
		global $database;
		$sql = "UPDATE ".self::$table_name;
		$sql .= " SET id={$this->id}, title=\"{$this->title}\", permissions=\"{$this->permissions}\" ";
		$sql .= "WHERE id={$this->id};";
		//die($sql);
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
	public function create() {
		global $database;
		$sql = "INSERT INTO ".self::$table_name."(title, permissions)";
		$sql .= " VALUES (\"{$this->title}\", \"{$this->permissions}\");";
		$database->query($sql);
		$this->id = $database->insert_id();
		return ($database->affected_rows() == 1) ? true : false;
	}

}

Level::init();

?>