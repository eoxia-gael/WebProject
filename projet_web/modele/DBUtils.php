<?php

class DBUtils{
	public $pdo;
	public $tableName;
	public $className;
	public $ctoArgs;
	
	function __construct($tableName, $className, $ctoArgs){
		$host = 'localhost';
		$db = 'projet_web';
		$login = 'root';
		$password = '';
		$params = array();
		
		$this->pdo = new PDO("mysql:host=$host;dbname=$db", $login, $password, $params);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		
		$this->tableName = $tableName;
		$this->className = $className;
		$this->ctoArgs = $ctoArgs;
	}
	
	public function save($dataArray){
		$questionMarks = '';
		for($i = 0; $i < count($dataArray); $i++){
			$questionMarks .= '?';
			if($i != count($dataArray) - 1){
				$questionMarks .= ',';
			}
		}
		
		$request = "insert into $this->tableName values(null,$questionMarks)";

		try{
			$result = $this->pdo->prepare($request);
			$result->execute($dataArray);
		}
		catch(Exception $e) {
			echo 'Exception -> ';
			var_dump($e->getMessage());
		}
		
		return $this->pdo->lastInsertId();
	}
	
	public function load($id){
		$request = $this->pdo->prepare("select * from $this->tableName where id = ?");
		$request->execute(array($id));
		$request->setFetchMode(PDO::FETCH_CLASS, $this->className, $this->ctoArgs);
		$row = $request->fetch();
		
		return $row;
	}
}