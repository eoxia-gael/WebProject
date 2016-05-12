<?php
require_once('DBUtil.php');
class Utilisateur{
	public static function load($userId){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('select * from utilisateurs where id = ?');
		$request->execute(array($userId));
		$row = $request->fetch();
		return $row;
	}
	
	public static function loadByEmail($email){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('select * from utilisateurs where email = ?');
		$request->execute(array($email));
		$row = $request->fetch();
		return $row;
	}
	
	public static function checkConnection($dataArray){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('select * from utilisateurs where email = ? and mot_de_passe = ?');
		$request->execute($dataArray);
		$row = $request->fetch();
		return $row;
	}
	
	public static function save($dataArray){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('insert into utilisateurs values(null, ?, ?, ?)');
		$request->execute($dataArray);
		return $pdo->lastInsertId();
	}
	
	public static function updateMail($dataArray){
		try{
			$pdo = DBUtil::getConnection();
			$request = $pdo->prepare('update utilisateurs set email = ? where id = ?');
			$request->execute($dataArray);
			return true;
		}
		catch(Exception $e){
			return false;
		}
	}
	
	public static function updatePassword($dataArray){
		try{
			$pdo = DBUtil::getConnection();
			$request = $pdo->prepare('update utilisateurs set mot_de_passe = ? where id = ?');
			$request->execute($dataArray);
			return true;
		}
		catch(Exception $e){
			return false;
		}
	}
}
