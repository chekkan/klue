<?php
require_once(LIB_PATH."Table.php");


class Event extends Table {

	protected static $table_name = "events";
	
	public $id;
	public $title;
	public $venue;
	public $date;
	public $description;
	public $time_created; //TODO: Wont get updated when updating the event
	public $user_id;
	public $draft;

    //TODO: Could be a candidate to move up to parent class Table
	public static function exists($id) {
		global $database;
		$sql = "SELECT id FROM events WHERE id = {$id};";
		$result = $database->query($sql);
		return ($database->num_rows($result) == 1) ? true : false;
	}

	public function create() {
		$this->time_created = time(); //TODO: change to datetime type in database
		parent::create();
	}

}


?>