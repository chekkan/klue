<?php

require_once(LIB_PATH."Table.php");

//TODO: This class should be called Role?
class Level extends Table {

	protected static $table_name = "levels";
	
	public $id;
	public $title;
	public $permissions;
	
	public function get_permission($module="") {
		switch($module) {
			case "Gallery":
				return $this->permissions[0];
			break;
			case "News":
				return $this->permissions[1];
			break;
			case "Events":
				return $this->permissions[2];
			break;
			case "Comments":
				return $this->permissions[3];
			break;
			default:
				return false;
			break;
		}
	}

}

Level::init();

?>