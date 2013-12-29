<?php

require_once(LIB_PATH."Table.php");

class User extends Table{

	protected static $table_name = "users";
	
	public $id;
	public $email;
	public $password;
	public $first_name;
	public $last_name;
	public $date_of_birth;
	public $level_id;
	public $register_date;
	
	public function full_name() {
		return $this->first_name ." ". $this->last_name;
	}
	
	// checks to see if a user is an administrator
	public static function is_admin($id) {
		$user = self::find_by_id($id);
		return ($user->level_id == 1) ? true : false;
	}
	
	// return the user group
	public function user_group() {
		global $database;
		$sql = "SELECT * FROM levels WHERE id={$this->level_id};";
		$result = $database->query($sql);
		$row = $database->fetch_assoc($result);
		return $row['title'];
	}
	
	public function create() {
        $this->register_date = time(); // TODO this should be of type datetime
		parent::create();
	}
	
	public function update() {		
		global $database;
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= "email=\"{$this->email}\", first_name=\"{$this->first_name}\", 
				last_name=\"{$this->last_name}\", date_of_birth=\"{$this->date_of_birth}\" ";
		if(isset($this->level_id)) {
			$sql .= ", level_id={$this->level_id} "; // TODO: Make sure this is needed
		}
		$sql .= "WHERE id=".$this->id.";";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
}

User::init();

?>