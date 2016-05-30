<?php
if(isset($_GET['action'])){
	switch($_GET['action']){
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