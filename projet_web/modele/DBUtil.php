<?php

class DBUtil{
	private static $pdo;
	
	public static function getConnection(){
		if(!isset(self::$pdo)){
			$host = 'localhost';
			$db = 'projet_web';
			$login = 'root';
			$password = '';
			$params = array();
			
			self::$pdo = new PDO("mysql:host=$host;dbname=$db", $login, $password, $params);
			self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
		}
		
		return self::$pdo;
	}
}