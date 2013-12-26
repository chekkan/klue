<?php

require_once("Table.php");

class EventComment extends Table {

	protected static $table_name = "event_comments";
	protected static $db_fields = array();
	
	public $id;
	public $message;
	public $event_id;
	public $user_id;
	public $time_posted;
	
	public static function find_by_event($id=0) {
		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE event_id = {$id};";
		return parent::find_by_sql($sql);
	}

}

EventComment::init();

?>