<?php

require_once(LIB_PATH."Table.php");

class Comment extends Table {

	protected static $table_name = "photo_comments";
	public static $db_fields = array();
	
	public $id;
	public $message;
	public $photo_id;
	public $user_id;
	public $time_posted;
	
	public static function find_by_photo($id=0) {
		$sql = "Select * FROM ".self::$table_name;
		$sql .= " WHERE photo_id = {$id};";
		return self::find_by_sql($sql);
	}
	
	public function create() {
		$this->time_posted = date("Y-m-d H:i:s");
        parent::create();
	}

}

Comment::init();

?>