<?php

require_once(LIB_PATH."Table.php");

class Photograph extends Table {
	
	protected static $table_name = "photographs";
	
	public $id;
	public $filename;
	public $type;
	public $size;
	public $caption;
	public $album_id;
	public $user_id;
	public $date_taken;
	public $location;
	public $time_uploaded;
	
	private $temp_path;
	protected $upload_dir = "uploads";
	public $errors = array();
	
	protected $upload_errors = array(
		// http://www.php.net/manual/en/features.file-upload.errors.php
		UPLOAD_ERR_OK 			=>	"No errors.",
		UPLOAD_ERR_INI_SIZE		=>	"Larger than upload_max_filesize.",
		UPLOAD_ERR_FORM_SIZE 	=>	"Larger than MAX_FILE_SIZE.",
		UPLOAD_ERR_PARTIAL		=>	"Partial upload.",
		UPLOAD_ERR_NO_FILE		=>	"No file.",
		UPLOAD_ERR_NO_TMP_DIR	=>	"No temporary directory.",
		UPLOAD_ERR_CANT_WRITE	=>	"Can't write to disk.",
		UPLOAD_ERR_EXTENSION	=>	"File upload stopped by extension."
	);

	// pass in $_FILE['uploaded_file'] as an argument
	public function attach_file($file) {
		// Perform error checking on the form parameters
		if(!$file || empty($file) || !is_array($file)) {
			$this->errors[] = "No file was uploaded.";
			return false;
		}
		else if($file['error'] != 0) {
			$this->errors[] = $this->upload_errors[$file['error']];
			return false;
		}
		else {
			// Set object attributes to the form parameters.
			$this->temp_path 	= $file['tmp_name'];
			$this->filename 	= basename($file['name']);
			$this->type 		= $file['type'];
			$this->size 		= $file['size'];
			
			return true;
		}
	}
	
	public function save() {
		// a new record wont have an id yet
		if(isset($this->id)) {
			// Really just to update the caption
			$this->update();
		}
		else {
			// make sure there are no errors
			
			// cant save if there are pre-existing errors
			if(!empty($this->errors)) { return false; }
						
			// make sure the caption is not too long for the DB
			if(strlen($this->caption) > 255) {
				$this->errors[] = "The caption can only be 255 characters long.";
				return false;
			}
			
			// Determine the target_pat
			$target_path = $this->upload_dir."/".$this->filename;
						
			// Make sure a file doesn't already exist in the target location
			if(file_exists($target_path)) {
				$this->errors[] = "The file {$this->filename} already exists.";
				return false;
			}
			
			// attempt to move the file
			if(move_uploaded_file($this->temp_path, $target_path)) {
				// Success
				// save a corresponding entry to the database
				if($this->create()) {
					// we are done with temp_path, the file isn't there anymore
					unset($this->temp_path);
					return true;
				}
			}
			else {
				// File was not moved
				$this->errors[] = "The file upload failed, possibly due to incorrect permission on the upload folder.";
				return false;
			}			
		}
	}
	
	public function image_path() {
		return $this->upload_dir."/".$this->filename;
	}
	
	public function size_as_text() {
		if($this->size < 1024) {
			return "{$this->size} bytes";
		}
		else if($this->size < 1048576) {
			$size_kb = round($this->size/1024);
			return "{$size_kb} KB";
		}
		else {
			$size_mb = round($this->size/1048576, 1);
			return "{$size_mb} MB";
		}
	}

	public static function find_by_album($id=0) {
		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE album_id={$id};";
		return self::find_by_sql($sql);
	}

    //TODO: can we use the parent method?
	public function create() {
		global $database;
		
		// $this->time_created = date("Y-m-d H:i:s");
		$attributes = $this->attributes();
		
		$sql = "INSERT INTO ".self::$table_name."(";
		// take out the id for insert sql
		$array_keys = array_keys($attributes);
		array_shift($array_keys);
		$sql .= join(", ", $array_keys);
		$sql .= ") VALUES ('";
		// take out the value for id from sql
		$array_values = array_values($attributes);
		array_shift($array_values);
		$sql .= join("', '", $array_values);
		$sql .= "');";
		$database->query($sql);
		if($database->affected_rows() == 1)	{
			$this->id = $database->insert_id();
			return true;
		}
		else { 
			return false;
		}
	}

    //TODO: can we use the parent metho?
	public function update() {
		global $database;
		$attributes = $this->attributes();
		$attribute_pairs = array();
		foreach($attributes as $key => $value) {
			$attribute_pairs[] = "{$key}='{$value}'";
		}
		$sql = "UPDATE ".self::$table_name." SET ";
		$sql .= join(", ", $attribute_pairs);
		$sql .= " WHERE id=".$this->id.";";
		$database->query($sql);
		return ($database->affected_rows() == 1) ? true : false;
	}

}

Photograph::init();

?>