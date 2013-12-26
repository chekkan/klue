<?php

require_once("Database.php");
require_once("Table.php");


class Settings extends Table {

	protected static $table_name = "settings";
	protected static $db_fields = array();

	public $id;
	public $site_name;
	public $site_description;
	
	function __construct() {
		global $database;
		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " LIMIT 1;";
		$result = $database->query($sql);
		$row = $database->fetch_assoc($result);
		$this->id = $row['id'];
		$this->site_name = $row['site_name'];
		$this->site_description = $row['site_description'];
	}
	
	public function save() {
		return $this->update();
	}
	
	public function update() {
		global $database;
		$sql = "UPDATE ".self::$table_name;
		$sql .= " SET site_name = \"{$this->site_name}\", ";
		$sql .= "site_description = \"{$this->site_description}\"";
		$sql .= " WHERE id = {$this->id};";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

}

Settings::init();
$settings = new Settings();

?>