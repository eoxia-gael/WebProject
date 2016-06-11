<?php
error_reporting(E_ALL);
	ini_set('display_errors', 1);
	
require_once('DBUtil.php');
class Incident{
	public static function save($dataArray){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('insert into incidents values(null, ?, ?, ?, ?)');
		$request->execute($dataArray);
		return $pdo->lastInsertId();
	}
	
	public static function findInArea($rayon, $latitude, $longitude){
		$pdo = DBUtil::getConnection();
		$request = $pdo->prepare('SELECT * FROM incidents WHERE :rayon > ACOS(SIN(latitude * 2 * PI() / 360) * SIN(:latitude * 2 * PI() / 360) + COS(latitude * 2 * PI() / 360) * COS(:latitude * 2 * PI() / 360) * COS(longitude * 2 * PI() / 360 - :longitude * 2 * PI() / 360)) * 6371');
		$request->bindParam(':rayon', $rayon);
		$request->bindParam(':latitude', $latitude);
		$request->bindParam(':longitude', $longitude);
		$request->execute();
		return $request->fetchAll();
	}
	
	public static function isReported($latitude, $longitude, $typeIncidentId){
		$listIncidents = self::findInArea(100, $latitude, $longitude);
		foreach($listIncidents as $incident){
			if($incident['type_incident_id'] == $typeIncidentId){
				return $incident['id'];
			}
		}
		
		return false;
	}
}