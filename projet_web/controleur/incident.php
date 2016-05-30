<?php
if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'save':
			if(isset($_POST['custom_incident']) && isset($_POST['latitude']) && isset($_POST['longitude']) && isset($_POST['type_incident_id']) && isset($_POST['commentaire'])){
				$customIncident = $_POST['custom_incident'];
				$latitude = $_POST['latitude'];
				$longitude = $_POST['longitude'];
				$typeIncidentId = $_POST['type_incident_id'];
				$commentaire = $_POST['commentaire'];
				
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
						if(Incident::isReported($latitude, $longitude, $typeIncidentId)){
							if(Incident::save(array($customIncident, $latitude, $longitude, $typeIncidentId, $commentaire)){
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