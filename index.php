<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<title>Maps</title>
		<link rel="stylesheet" href="style.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> 
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
		<script src="https://maps.google.com/maps/api/js?libraries=geometry"></script> <!-- https://maps.google.com/maps/api/js?key=YOUR_API_KEY&signed_in=true&libraries=geometry&callback=initMap -->
		<script src="script.js"></script>
	</head>
	<body>
		<div data-role="page" id="map-page" data-url="map-page">
			<div data-role="panel" id="panelLogin" data-display="overlay" class="panels">
				<h2>Login</h2>
				<input type="text" name="text-basic" id="email_login" value="" placeholder="Email">
				<input type="password" name="password" id="password_login" value="" autocomplete="off" placeholder="Password">
				<a href="#" class="ui-shadow ui-btn ui-corner-all submit-1" id="login_submit" data-role="button" role="button">Connect</a>
				<ul id="notifications_login"></ul>
				<a id="panelSignup" href="#signup-page" class="ui-shadow ui-btn ui-corner-all submit-1" data-role="button" role="button">Signup</a>
			</div>
			<div data-role="panel" id="panelEvent" data-display="overlay" class="panels">
				<h2>Event</h2>
					<input type="hidden" id="event-latitude" value>
					<input type="hidden" id="event-longitude" value>
					<fieldset data-role="controlgroup">
						<?php
						$data = http_build_query(array('page' => 'type_incident', 'action' => 'findAll'));
						$options = array( 'http' => array( 'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen( $data ) . "\r\n", 'method'  => "POST", 'content' => $data ) );
						$url = 'http' . ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? 's' : '') . '://' . $_SERVER["SERVER_NAME"] . (($_SERVER["SERVER_PORT"] == '80' || $_SERVER["SERVER_PORT"] == '443') ? '' : ':' . $_SERVER["SERVER_PORT"]) . ((substr($_SERVER['REQUEST_URI'], -1) == '/') ? $_SERVER['REQUEST_URI'] : dirname($_SERVER['REQUEST_URI']));
						$context = stream_context_create($options);
						$request = fopen($url . 'controleur/controleur.php', 'r', false, $context);
						$response = json_decode( stream_get_contents( $request ) );
						if ($response->success) {
							foreach($response->data as $event_type) {
						?>
						<input type="radio" name="event-choice" id="event-choice-<?php echo $event_type->id; ?>" value="<?php echo $event_type->id; ?>">
						<label for="event-choice-<?php echo $event_type->id; ?>"><?php echo $event_type->nom; ?></label>
						<?php } } ?>
						<input type="radio" name="event-choice" id="event-choice-custom" value="custom_message" checked="checked">
						<label for="event-choice-custom"><textarea id="event-choice-custom-textarea" placeholder="Your custom mesage here..."></textarea></label>
					</fieldset>
				<a href="#" class="ui-shadow ui-btn ui-corner-all submit-1" id="event_send_submit" data-role="button" role="button">Send</a>
				<ul id="notifications_event"></ul>
				<button id="logout" class="ui-shadow ui-btn ui-corner-all submit-1">Logout</button>
			</div>
			<div data-role="header" data-theme="a" id="map-header">
				<a href="#panelLogin" id="open_menu" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all"></a><h1>Maps</h1><button id="centerMap" class="ui-btn ui-shadow">Follow</button>
			</div>
			<div role="main" class="ui-content" id="map-canvas"></div>
		</div>
		<div data-role="page" id="signup-page" data-url="signup-page">
			<a href="#" data-rel="back" class="ui-shadow ui-btn ui-corner-all ui-btn-inline ui-btn-icon-left ui-icon-carat-l backBtn" data-role="button" role="button">Back</a>
			<form id="form-signup">
				<h1>Signup</h1>
				<input type="text" name="text-basic" id="email_signup" value="" placeholder="Email">
				<input type="password" name="password" id="password_signup" value="" autocomplete="off" placeholder="Password">
				<a href="#" id="signup_submit" data-rel="back" class="ui-shadow ui-btn ui-corner-all submit-1" data-role="button" role="button">Submit</a>
				<ul id="notifications_signup"></ul>
			</form>
		</div>
	</body>
</html>