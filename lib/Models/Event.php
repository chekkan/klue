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

    public function create() {
		$this->time_created = time(); //TODO: change to datetime type in database
		parent::create();
	}

}

Event::init();

?>