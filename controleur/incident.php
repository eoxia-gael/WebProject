<?php
if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'save':
			if(isset($_POST['latitude']) && isset($_POST['longitude'])){
				$customIncident = $_POST['custom_incident'];
				$latitude = $_POST['latitude'];
				$longitude = $_POST['longitude'];
				$typeIncidentId = (isset($_POST['type_incident_id']) && $_POST['type_incident_id'] != '') ? $_POST['type_incident_id'] : null;
				$commentaire = (isset($_POST['commentaire'])) ? $_POST['commentaire'] : '';
				
				if($latitude == ''){
					$result['errors'][] = 'La latitude est vide';
				}
				else if($longitude == ''){
					$result['errors'][] = 'La longitude est vide';
				}
				else if($typeIncidentId == '' && $customIncident == ''){
					$result['errors'][] = 'Custom incident est vide';
				}
				else{
					if(isset($_SESSION['user'])){
						$userId = $_SESSION['user']['id'];
						if($idIncident = Incident::isReported($latitude, $longitude, $typeIncidentId)){
							if(UtilisateursIncidents::save(array($userId, $idIncident, $commentaire))){
								$result['success'] = true;
							}
							else{
								$result['errors'][] = 'Incident déjà reporté par l\'utilisateur';
							}
						}
						else{
							if($idIncident = Incident::save(array($customIncident, $latitude, $longitude, $typeIncidentId))){
								if(UtilisateursIncidents::save(array($userId, $idIncident, $commentaire))){
									$result['success'] = true;
								}
								else{
									$result['errors'][] = 'Error lors de l\'insertion d\'un UtilisateurIncident';
								}
							}
							else{
								$result['errors'][] = 'Error lors de l\'insertion d\'un incident';
							}
						}
					}
					else{
						$result['errors'][] = 'Non connecté';
					}
				}
			}
			else{
				$result['errors'][] = 'Latitude ou longitude not set';
			}
			break;
		case 'findInArea':
			if(isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['rayon'])){
				$latitude = $_POST['latitude'];
				$longitude = $_POST['longitude'];
				$rayon = $_POST['rayon'];
				
				if($latitude == ''){
					$result['errors'][] = 'La latitude est vide';
				}
				else if($longitude == ''){
					$result['errors'][] = 'La longitude est vide';
				}
				else if($rayon == ''){
					$result['errors'][] = 'Le rayon est vide';
				}
				else{
					if(isset($_SESSION['user'])){
						$result['success'] = true;
						$result['data'] = Incident::findInArea($rayon, $latitude, $longitude);
					}
					else{
						$result['errors'][] = 'Non connecté';
					}
				}
			}
			break;
	}
}