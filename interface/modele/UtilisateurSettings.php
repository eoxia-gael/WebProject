<?php
require_once('DBUtil.php');
class UtilisateurSettings{
	public static function loadByUserId($userId){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('select utilisateur_settings.* from utilisateur_settings inner join utilisateurs on utilisateurs.parametres_utilisateur_id = utilisateur_settings.id where utilisateurs.id = ?');
		$request->execute(array($userId));
		$row = $request->fetch();
		
		return $row;
	}
	
	public static function save($dataArray){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('insert into utilisateur_settings values(null, ?, ?, ?, ?)');
		$request->execute($dataArray);
		return $pdo->lastInsertId();
	}
	
	public static function update($dataArray){
		try{
			$pdo = DBUtil::getConnection();
			$request = $pdo->prepare('update utilisateur_settings set credibilite_min = ?, distance_incident = ?, notifications_actives = ?, avatar = ? where id = ?');
			$request->execute($dataArray);
			return true;
		}
		catch(Exception $e){
			return false;
		}
	}
}