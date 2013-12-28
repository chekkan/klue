<?php

require_once(LIB_PATH."Table.php");

class Album extends Table {
	
	protected static $table_name = "albums";
	
	public $id;
	public $title;
	public $location;
	public $description;
	public $user_id;
	public $date_taken;
	public $time_created;
	public $time_modified;
	
	public static function exists($id) {
		global $database;
		$sql = "SELECT id FROM ".self::$table_name." WHERE id = {$id};";
		$result = $database->query($sql);
		
		if($database->num_rows($result) == 1) {
			return true;
		}
		else {
			return false;
		}
	}

	public function create() {
        // TODO: this could be done in parent method.
        // TODO: time_created and time_modified fields are candidates for parent
		$this->time_created = $this->time_modified = date("Y-m-d H:i:s");
		return parent::create();
	}
	
	public function update() {
		//TODO: can move this up to the parent method
		$this->time_modified = date("Y-m-d H:i:s");
		return parent::update();
	}

}

Album::init();

?>