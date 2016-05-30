<?php
require_once('DBUtil.php');
class Utilisateur{
	public static function findAll(){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('select * from type_incidents');
		$request->execute();
		return $requst->fetchAll();
	}
}