<?php

require_once("Database.php");
require_once("Table.php");

class News extends Table{

	protected static $table_name = "news";
	protected static $db_fields = array();
	
	public $id;
	public $title;
	public $message;
	public $user_id;
	public $draft;
	public $time_posted;
	
	public function get_summary() {
		return $this->message;
	}

	public static function exists($id=0) {
		global $database;
		$sql = "SELECT id FROM ".self::$table_name;
		$sql .= " WHERE id={$id};";
		$result = $database->query($sql);
		return ($database->num_rows($result) == 1) ? true : false;
	}
	
}

News::init();

?>