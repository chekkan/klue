<?php

require_once("Database.php");
require_once("Table.php");

class User extends Table{

	protected static $table_name = "users";
	protected static $db_fields = array();
	
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
		global $database;
		$sql = "INSERT INTO users(email, password, first_name, last_name, date_of_birth, level_id, register_date)
					VALUES(\"{$this->email}\", '".sha1($this->password)."', \"{$this->first_name}\",
					 		\"{$this->last_name}\", \"{$this->date_of_birth}\", {$this->level_id}, ".time().");";
		$database->query($sql);
		if($database->affected_rows() == 1) {
			$this->id = $database->insert_id();
			return true;
		}
		else {
			return true;
		}
	}
	
	public function update() {		
		global $database;
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= "email=\"{$this->email}\", first_name=\"{$this->first_name}\", 
				last_name=\"{$this->last_name}\", date_of_birth=\"{$this->date_of_birth}\" ";
		if(isset($this->level_id)) {
			$sql .= ", level_id={$this->level_id} ";
		}
		$sql .= "WHERE id=".$this->id.";";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}
	
}

User::init();

?>