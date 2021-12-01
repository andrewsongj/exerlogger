<?php
class DbUtil{
	//superuser
	/*public static $loginUser = "cm4qrk"; 
	public static $loginPass = "DBPassword!";
	public static $host = "mysql01.cs.virginia.edu";
	public static $schema = "cm4qrk";*/ // DB Schema

	//normal user
	public static $loginUser = "cm4qrk_a"; 
	public static $loginPass = "Fall2021!!";
	public static $host = "mysql01.cs.virginia.edu";
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