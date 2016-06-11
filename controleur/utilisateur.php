<?php
if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'inscription':
			if(isset($_POST['mot_de_passe']) && isset($_POST['email'])){
				$motDePasse = $_POST['mot_de_passe'];
				$email = $_POST['email'];
				
				if($motDePasse == ''){
					$result['errors'][] = 'Le mot de passe est vide';
				}
				else if($email == ''){
					$result['errors'][] = 'L\'email est vide';
				}
				else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$result['errors'][] = 'Email non valide';
				}
				else{
					if(!Utilisateur::loadByEmail($email)){
						$settingsId = UtilisateurSettings::save(array(3, 15, true, 'noPicture.png'));
						$dataArray = array(sha1($motDePasse), $email, $settingsId);
						Utilisateur::save($dataArray);
						$result['success'] = true;
						ini_set('session.gc_maxlifetime', 3600 * 24 * 365 * 10);
						$result['data']['token'] = md5($email.'_privateKeyForEncription');
						session_id($result['data']['token']);
						session_start();
						$_SESSION['user'] = $user;
					}
					else{
						$result['errors'][] = 'Email déjà existant';
					}
				}
			}
			break;
		case 'connexion':
			if(isset($_POST['email']) && isset($_POST['mot_de_passe'])){
				$motDePasse = $_POST['mot_de_passe'];
				$email = $_POST['email'];
				
				if($motDePasse == ''){
					$result['errors'][] = 'Le mot de passe est vide';
				}
				else if($email == ''){
					$result['errors'][] = 'L\'email est vide';
				}
				else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$result['errors'][] = 'Email non valide';
				}
				else if($user = Utilisateur::checkConnection(array($email, sha1($motDePasse)))){
					ini_set('session.gc_maxlifetime', 3600 * 24 * 365 * 10);
					$result['data']['token'] = md5($email.'_privateKeyForEncription');
					session_id($result['data']['token']);
					session_start();
					$_SESSION['user'] = $user;
					$result['success'] = true;
				}
				else{
					$result['errors'][] = 'Echec de la connexion';
				}
			}
			break;
		case 'update':
			if(isset($_POST['mot_de_passe']) && isset($_POST['email'])){
				$motDePasse = $_POST['mot_de_passe'];
				$email = $_POST['email'];
				
				if($motDePasse == ''){
					$result['errors'][] = 'Le mot de passe est vide';
				}
				else if($email == ''){
					$result['errors'][] = 'L\'email est vide';
				}
				else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
					$result['errors'][] = 'Email non valide';
				}
				else{
					if(isset($_SESSION['user'])){
						$userId = $_SESSION['user']['id'];
						if($_SESSION['user']['email'] != $email){
							if(Utilisateur::updateMail(array($email, $_SESSION['user']['id']))){
								$_SESSION['user']['email'] = $email;
								$result['success'] = true;
							}
						}
						
						if($_SESSION['user']['mot_de_passe'] != sha1($motDePasse)){
							if(Utilisateur::updatePassword(array(sha1($motDePasse), $_SESSION['user']['id']))){
								$_SESSION['user']['mot_de_passe'] = sha1($motDePasse);
								$result['success'] = true;
							}
						}
					}
					else{
						$result['errors'][] = 'Non connecté';
					}
				}
			}
			break;
		case 'logout':
			session_unset();
			session_destroy();
			$result['success'] = true;
			break;
	}
}