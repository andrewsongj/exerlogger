<?php
class DbUtil{
	public static $loginUser = "joyul"; 
	public static $loginPass = "whdbfl<33";
	public static $host = "localhost:3307"; // local host
	public static $schema = "final"; // DB Schema
	
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