<?php
if(isset($_POST['action'])){
	switch($_POST['action']){
		case 'findAll':
			$result['success'] = true;
			$result['data'] = TypeIncident::findAll();
			break;
	}
}