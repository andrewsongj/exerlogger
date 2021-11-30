<?php
class DbUtil{
	public static $loginUser = "cm4qrk"; 
	public static $loginPass = "DBPassword!";
	public static $host = "mysql01.cs.virginia.edu"; // local host
	public static $schema = "cm4qrk_final"; // DB Schema
	
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
