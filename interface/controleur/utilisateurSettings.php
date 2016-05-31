<?php
if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'update':
			if(isset($_POST['credibilite_min']) && isset($_POST['distance_incident']) && isset($_POST['notifications_actives']) && isset($_POST['avatar'])){
				$credibiliteMin = $_POST['credibilite_min'];
				$distanceIncident = $_POST['distance_incident'];
				$notificationsActives = $_POST['notifications_actives'];
				$avatar = $_POST['avatar'];
				
				if($credibiliteMin == ''){
					$result['errors'][] = 'La crédibilité minimum est vide';
				}
				else if($distanceIncident == ''){
					$result['errors'][] = 'La distance de l\'incident est vide';
				}
				else if($notificationsActives == ''){
					$result['errors'][] = 'Notification actives est vide';
				}
				else{
					if(isset($_SESSION['user'])){
						$userId = $_SESSION['user']['id'];
						$userSetting = UtilisateurSettings::loadByUserId($userId);
						if(UtilisateurSettings::update(array($credibiliteMin, $distanceIncident, $notificationsActives, $avatar, $userSetting['id']))){
							$result['success'] = true;
						}
					}
					else{
						$result['errors'][] = 'Non connecté';
					}
				}
			}
			break;
	}
}