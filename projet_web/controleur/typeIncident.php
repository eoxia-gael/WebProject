<?php
if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'findAll':
			if(isset($_SESSION['user'])){
				$result['success'] = true;
				$result['data'] = TypeIncident::findAll($rayon, $latitude, $longitude);
			}
			else{
				$result['errors'][] = 'Non connecté';
			}
			break;
	}
}