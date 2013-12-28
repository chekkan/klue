<?php

require_once(LIB_PATH."Table.php");

class BlogComment extends Table {

	protected static $table_name = "blog_comments";
	protected static $db_fields = array();
	
	public $id;
	public $message;
	public $blog_id;
	public $user_id;
	public $time_posted;
	
	public static function find_by_blog($id=0) {
		$sql = "SELECT * FROM ".self::$table_name;
		$sql .= " WHERE blog_id = {$id};";
		return parent::find_by_sql($sql);
	}

}

BlogComment::init();

?>