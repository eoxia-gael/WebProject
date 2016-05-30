<?php
require_once('DBUtil.php');
class TypeIncident {
	public static function findAll(){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('select * from type_incidents');
		$request->execute();
		return $request->fetchAll();
	}
}