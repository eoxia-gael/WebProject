<?php
	session_start();
	require_once('../modele/Utilisateur.php');
	require_once('../modele/UtilisateurSettings.php');
	if(isset($_POST['page'])){
		$page = $_POST['page'];
		$result = array('success' => false, 'errors' => array());
		
		switch($page){
			case 'utilisateur':
				require_once('utilisateur.php');
				break;
			case 'utilisateur_settings':
				require_once('utilisateurSettings.php');
				break;
			case 'incident':
				require_once('incident.php');
				break;
		}
		
		echo json_encode($result);
	}