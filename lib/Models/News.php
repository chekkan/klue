<?php

require_once(LIB_PATH."Table.php");

class News extends Table{

	protected static $table_name = "news";
	
	public $id;
	public $title;
	public $message;
	public $user_id;
	public $draft;
	public $time_posted; // editing news will make this field null
	
	public function get_summary() {
		return $this->message;
	}

	public function create() {
		global $database;
		$this->time_posted = time(); //TODO: change type to datetime
		parent::create();
	}
	
}

News::init();

?>