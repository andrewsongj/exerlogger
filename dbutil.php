<?php
class DbUtil{
	// public static $loginUser = "joyul"; 
	// public static $loginPass = "whdbfl<33";
	// public static $host = "localhost:3307"; // local host
	// public static $schema = "final"; // DB Schema
	public static $loginUser = "cm4qrk_a"; 
	public static $loginPass = "Fall2021!!";
	public static $host = "mysql01.cs.virginia.edu"; // local host
	public static $schema = "cm4qrk"; // DB Schema
  
	public static function loginConnection(){
		$db = new mysqli(DbUtil::$host, DbUtil::$loginUser, DbUtil::$loginPass, DbUtil::$schema);
	
		if($db->connect_errno){
			echo("Could not connect to db");
			$db->close();
			exit();
		}
		
		return $db;
	}
	
}
?>
