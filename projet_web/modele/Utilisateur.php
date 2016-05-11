<?php
require_once('DBUtils.php');

class Utilisateur{
	private static $dbUtils;
	public $id;
	public $pseudo;
	public $mot_de_passe;
	public $email;
	public $avatar;
	public $parametres_utilisateur_id;
	
	function __construct($pseudo, $mot_de_passe, $email, $avatar, $parametres_utilisateur_id){
		self::$dbUtils = new DBUtils('utilisateurs', 'Utilisateur', array('pseudo', 'mot_de_passe', 'email', 'avatar', 'parametres_utilisateur_id'));
		$this->pseudo = $pseudo;
		$this->mot_de_passe = $mot_de_passe;
		$this->email = $email;
		$this->avatar = $avatar;
		$this->parametres_utilisateur_id = $parametres_utilisateur_id;
	}
	
	public function save(){
		$dataArray = array($this->pseudo, $this->mot_de_passe, $this->email, $this->avatar, $this->parametres_utilisateur_id);
		$this->id = self::$dbUtils->save($dataArray);
	}

	public static function load($userId){
		$row = self::$dbUtils->load($userId);
		
		return $row;
	}
}
