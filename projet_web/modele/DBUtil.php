<?php

class DBUtil{
	public static function getConnection(){
		$host = 'localhost';
		$db = 'projet_web';
		$login = 'root';
		$password = '';
		$params = array();
		
		$pdo = new PDO("mysql:host=$host;dbname=$db", $login, $password, $params);
		
		return $pdo;
	}
}