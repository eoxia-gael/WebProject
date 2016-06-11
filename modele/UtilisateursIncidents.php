<?php
require_once('DBUtil.php');
class UtilisateursIncidents{
	public static function save($dataArray){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('insert into utilisateurs_incidents values(?, ?, ?)');
		if($request->execute($dataArray)){
			return true;
		}
		else{
			return false;
		}
	}
}