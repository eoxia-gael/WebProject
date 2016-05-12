<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function __autoload($classname) {
	$dir = array('controleur','model','vue');
	foreach ($dir as $d) {
		$filename = './'.$d.'/'. $classname .'.php';
		if (file_exists($filename)) {
			include_once($filename);
			break;
		}
	}
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
		var_dump($_SESSION);
	?>
	<form method="post" action="controleur/controleur.php">
		<input type="text" placeholder="Page" name="page">
		<input type="text" placeholder="Action" name="action">
		<input type="text" placeholder="email" name="email">
		<input type="text" placeholder="mot_de_passe" name="mot_de_passe">
		<button>Go</button>
	</form>
</body>
</html>