<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
</head>
<body>
	<?php
		require('modele/Utilisateur.php');
		$user = new Utilisateur('Zizi', sha1('zizi'), 'zizi@pipi.fr', 'zizi.png', 1);
		$user->save();
		var_dump($user);
		$user2 = Utilisateur::load($user->id);
		var_dump($user2);
	?>
</body>
</html>