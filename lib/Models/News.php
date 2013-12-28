<?php

require_once(LIB_PATH."Database.php");
require_once(LIB_PATH."Table.php");

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

	public function create() {
		global $database;
		$this->time_posted = time();
		$sql = "INSERT INTO ".self::$table_name."(title, message, user_id, draft, time_posted)
				VALUES(\"{$this->title}\", \"{$this->message}\", {$this->user_id}, {$this->draft}, {$this->time_posted});";
		$result = $database->query($sql);
		if($database->affected_rows() == 1) {
			$this->id = $database->insert_id();
			return true;
		}
		else {
			return false;
		}
	}
	
}

News::init();

?>