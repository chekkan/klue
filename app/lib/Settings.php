<?php

require_once("../../lib/Database.php");
require_once("../../lib/Table.php");


//TODO: This class shouldn't extend from Table
// exposes create method which it shouldn't
class Settings extends Table {

	protected static $table_name = "settings";

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

}

Settings::init();
$settings = new Settings();

?>