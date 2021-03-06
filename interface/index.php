<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
		<title>Maps</title>
		<style>
			#map-page, #map-canvas {
				width: 100%;
				height: calc(100% - 44px);
				padding: 0;
			}
			#map-page {
				height: 100%;
			}
			.panels {
				background: #e9e9e9 !important;
			}
			.panels h2 {
				margin: 0;
				color: #666;
				padding-bottom: 15px;
				border-bottom: 1px solid #ccc;
				margin-bottom: 25px;
			}
			.panels {
				color: #666;
				margin: 0;
			}
			#panelLogin .ui-input-text {
				font-size: 20px;
			}
			#panelLogin .submit-1 {
				color: #666;
				margin-top: 20px;
			}
			#panelLogin #panelSignup {
				color: #666;
				left: 0;
				bottom: 0;
				position: absolute !important;
				top: auto !important;
				margin: 1em;
				width: 13em;
			}
			#panelEvent #logout {
				color: #666;
				left: 0;
				bottom: 0;
				position: absolute !important;
				top: auto !important;
				margin: 1em;
				width: 15em;
			}

			#login-page, #signup-page {
				background: #e9e9e9;
				margin: 0;
				padding: 0;
			}
			#login-page .backBtn, #signup-page .backBtn {
				margin: 20px;
			}
			#login-page .signupBtn {
				float: right;
				margin: 15px;
			}
			#login-page #form-login, #signup-page #form-signup {
				width: 320px;
				margin: auto;
				margin-top: 10%;
				height: 100%;
				vertical-align: middle;
			}
			#login-page #form-login h1, #signup-page #form-signup h1 {
				text-align: center;
				margin: 0;
				padding-bottom: 10px;
				border-bottom: 1px solid #ccc;
				margin-bottom: 30px;
			}
			#login-page #form-login .ui-input-text, #signup-page #form-signup .ui-input-text {
				font-size: 25px;
			}
			#login-page #form-login .submit-1, #signup-page #form-signup  .submit-1 {
				margin-top: 30px;
			}
			#panelLogin #notifications_login, #signup-page #form-signup #notifications_signup {
				padding: 0;
			}
			#panelLogin #notifications_login li, #signup-page #form-signup #notifications_signup li {
				color : red;
				list-style-type: none;
				text-align: center;
			}
		</style>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script> 
		<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquerymobile/1.4.5/jquery.mobile.min.js"></script>
		<script src="http://maps.google.com/maps/api/js"></script>
		<script>
			// ----------------------------------------- CenterControl ----------------------------------------- //
			function CenterControl(centerDiv) {
				var control = this;
				control.center_ = {lat: 0, lng: 0};

				control.geoLocMarker_ = new google.maps.Marker({
					position: control.center_,
					icon: {
						path: google.maps.SymbolPath.CIRCLE,
						scale: 5
					},
					map: map
				});

				control.goCenterUI_ = centerDiv;

				control.goCenterUI_.addEventListener('click', function() {
					map.setCenter(control.center_);
					control.followCenter_ = true;
					jQuery(control.goCenterUI_).addClass("ui-btn-active");
				});

				var firstChangedCenter = true;
				google.maps.event.addListener(map, 'center_changed', function() {
					if(firstChangedCenter) {
						firstChangedCenter = false;
					} else {
						control.followCenter_ = false;
						jQuery(control.goCenterUI_).removeClass("ui-btn-active");
					}
				});
			}
			CenterControl.prototype.goCenterUI_ = null;
			CenterControl.prototype.center_ = null;
			CenterControl.prototype.geoLocMarker_ = null;
			CenterControl.prototype.followCenter_ = true;
			CenterControl.prototype.view_distance_ = null;
			CenterControl.prototype.getCenter = function() {
				return this.center_;
			};
			CenterControl.prototype.setCenter = function(newPos) {
				var control = this;
				control.center_ = newPos;
				control.geoLocMarker_.setPosition(control.center_);
				if(control.followCenter_) {
					map.setCenter(control.center_);
					control.followCenter_ = true;
					jQuery(control.goCenterUI_).addClass("ui-btn-active");
				}
			};
			// ----------------------------------------- CenterControl ----------------------------------------- //

			// ----------------------------------------- MarkerControl ----------------------------------------- //
			function MarkerControl(markerList) {
				var control = this;
				for (index = 0; index < markerList.length; ++index) {
					control.addMarker(markerList.lat,markerList.lng,markerList.content);
					control.markerList_[index].markerInfo.close();
				}
				google.maps.event.addListener(map, 'click', function(position) {
					control.addMarker(position.latLng.lat(),position.latLng.lng(),control.markerControlDiv());
				});
				map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(control.markerControlDiv());
			}
			MarkerControl.prototype.markerList_ = [];
			MarkerControl.prototype.addMarker = function(lat,lng,content) {
				var addMarker = this;
				var newMarker = new google.maps.Circle({
					strokeColor: '#FF0000',
					strokeOpacity: 0.8,
					strokeWeight: 2,
					fillColor: '#FF0000',
					fillOpacity: 0.35,
					map: map,
					center: {lat: lat, lng: lng},
					radius: 100,
	    			draggable: true
				});
				var newMarkerInfo = new google.maps.InfoWindow({
					content: content
				});
				addMarker.closeMarker();
				newMarkerInfo.setPosition({lat: lat, lng: lng});
				//newMarkerInfo.open(map);
				google.maps.event.addListener(newMarker, 'click', function(position){
					for (index = 0; index < addMarker.markerList_.length; ++index) {
						addMarker.markerList_[index].markerInfo.close();
					}
					newMarkerInfo.setPosition(position.latLng);
					newMarkerInfo.open(map);
				});
				addMarker.markerList_.push({marker:newMarker,markerInfo:newMarkerInfo});
			}
			MarkerControl.prototype.closeMarker = function() {
				for (index = 0; index < this.markerList_.length; ++index) {
					this.markerList_[index].markerInfo.close();
				}
			}
			MarkerControl.prototype.markerControlDiv = function() {
				var markerControlDiv_ = this;
				var btn = document.createElement('a');
				btn.id = 'eventBtn';
				btn.className = 'ui-btn ui-corner-all ui-shadow';
				//btn.style.color = '#0099FF';
				btn.style.margin = '1em';
				btn.href = panel;
				btn.innerHTML = 'Event';
				btn.addEventListener('click', function() {
					markerControlDiv_.addMarker(centerControl.center_.lat,centerControl.center_.lng,markerControlDiv_.markerControlDiv());
				});
				return btn;
				/*<a href="#dashboard" class="ui-btn ui-icon-bars ui-btn-icon-notext ui-corner-all"></a>

				function jQueryMobileBtnPopup(identifier, color, title) {
					var btn = document.createElement('a');
					btn.id = identifier;
					btn.className = 'ui-btn ui-corner-all ui-shadow';
					btn.style.color = color;
					btn.style.display = 'inline-block';
					btn.style.margin = '.5em';
					btn.href = '#event';
					btn.dataset.rel = 'popup';
					btn.dataset.positionTo = 'window';
					btn.dataset.transition = 'pop';
					btn.innerHTML = title;
					btn.addEventListener('click', function() {
						var panel = document.getElementById('event');
						var eventTitle = document.createElement('h2');
						eventTitle.id = 'eventTitle';
						eventTitle.style.color = color;
						eventTitle.innerHTML = title;
						jQuery(panel).append(eventTitle);
						var eventFieldset = document.createElement('fieldset');
						eventFieldset.id = 'eventFieldset';
						eventFieldset.dataset.role = 'controlgroup';
						for (index = 0; index < message.length; ++index) {
							var inputMessage = document.createElement('input');
							inputMessage.type = 'radio';
							inputMessage.name = 'inputEvent';
							inputMessage.id = 'message'+index;
							inputMessage.value = message[index];
							jQuery(eventFieldset).append(inputMessage);
							var labelMessage = document.createElement('label');
							labelMessage.htmlFor = 'message'+index;
							labelMessage.innerHTML = message[index];
							jQuery(eventFieldset).append(labelMessage);
						}
						var inputCustomMessage = document.createElement('input');
						inputCustomMessage.type = 'radio';
						inputCustomMessage.name = 'inputEvent';
						inputCustomMessage.id = 'messageCustom';
						inputCustomMessage.value = 'messageCustom';
						jQuery(eventFieldset).append(inputCustomMessage);
						var labelCustomMessage = document.createElement('label');
						labelCustomMessage.for = 'messageCustom';
						var textareaCustomMessage = document.createElement('textarea');
						textareaCustomMessage.placeholder = 'Your message...';
						textareaCustomMessage.rows = '6';
						jQuery(labelCustomMessage).append(textareaCustomMessage);
						jQuery(eventFieldset).append(labelCustomMessage);
						jQuery(panel).append(eventFieldset);
						var sendEvent = document.createElement('a');
						sendEvent.id = 'sendEvent';
						sendEvent.href = '#';
						sendEvent.className = 'ui-btn ui-corner-all ui-shadow';
						sendEvent.innerHTML = 'Send';
						jQuery(panel).append(sendEvent);
					});
					return btn;
				}

				var controlUI = document.createElement('div');
				btn = jQueryMobileBtnPopup('information', '#0099FF', 'Information');
  				controlUI.appendChild(btn);
				btn = jQueryMobileBtnPopup('warning', '#FF9900', 'Warning');
  				controlUI.appendChild(btn);
				btn = jQueryMobileBtnPopup('danger', '#FF0000', 'Danger');
  				controlUI.appendChild(btn);

				return controlUI;*/
			}
			// ----------------------------------------- MarkerControl ----------------------------------------- //

			// ----------------------------------------- GeoLoc ----------------------------------------- //
			function GeoLoc() {
				var geoLoc_ = this;
				if(!(navigator && navigator.geolocation)) {
					alert('Geolocation is not supported');
				}
				function transition(easing,start,stop,timer,frame=0,sleepTime=50) {
					var finalDistanceLatitude = stop.latitude-start.lat;
					var finalDistanceLongitude = stop.longitude-start.lng;
					distanceLatitude = finalDistanceLatitude*easing((frame+1)*(sleepTime/timer));
					distanceLongitude = finalDistanceLongitude*easing((frame+1)*(sleepTime/timer));
					if(((sleepTime/timer)*frame)==1) {
						centerControl.setCenter({lat:stop.latitude,lng:stop.longitude});
						geoLoc_.positionList_.splice(0,1);
					} else {
						centerControl.setCenter({lat:start.lat+distanceLatitude,lng:start.lng+distanceLongitude});
						frame++;
						setTimeout(function() {transition(easing,start,stop,timer,frame,sleepTime);},sleepTime);
					}
				}
				function changePosition() {
					if(typeof geoLoc_.positionList_[0] != 'undefined') {
						transition(EasingFunctions.easeInOutQuad,centerControl.center_,geoLoc_.positionList_[0],900);
						delete geoLoc_.positionList_[0];
					}
					setTimeout(changePosition,10);
				}
				changePosition();
				/*function watchPosition() {
					navigator.geolocation.getCurrentPosition(geoLoc_.geoSuccess.bind(geoLoc_), geoLoc_.geoError.bind(geoLoc_), this.geoOptions);
					setTimeout(watchPosition,1000);
				}
				watchPosition();*/
				navigator.geolocation.watchPosition(this.geoSuccess.bind(this), this.geoError.bind(this), this.geoOptions);
			}
			GeoLoc.prototype.positionList_ = [];
			GeoLoc.prototype.error_ = [];
			GeoLoc.prototype.geoOptions = {
				enableHighAccuracy : true,
				maximumAge : 60000,
				timeOut : 0
			};
			GeoLoc.prototype.geoSuccess = function(newPosition) {
				this.positionList_.push(newPosition.coords);
			};
			GeoLoc.prototype.geoError = function(error) {
				this.error_.push(error);
				switch(error.code) {
					case 1:
						msg = 'permission denied';
						break;
					case 2:
						msg = 'position unavailable (error response from location provider)';
						break;
					case 3:
						msg = 'timed out';
						break;
					default:
						msg = 'unknown error';
						break;
				}
				alert('Error occurred. Code: ' + error.code + ' | Msg: ' + msg);
			};
			// ----------------------------------------- GeoLoc ----------------------------------------- //

			// ----------------------------------------- MathFunctions ----------------------------------------- //
			var EasingFunctions = {
				linear: function (t) { return t },
				easeInQuad: function (t) { return t*t },
				easeOutQuad: function (t) { return t*(2-t) },
				easeInOutQuad: function (t) { return t<.5 ? 2*t*t : -1+(4-2*t)*t },
				easeInCubic: function (t) { return t*t*t },
				easeOutCubic: function (t) { return (--t)*t*t+1 },
				easeInOutCubic: function (t) { return t<.5 ? 4*t*t*t : (t-1)*(2*t-2)*(2*t-2)+1 },
				easeInQuart: function (t) { return t*t*t*t },
				easeOutQuart: function (t) { return 1-(--t)*t*t*t },
				easeInOutQuart: function (t) { return t<.5 ? 8*t*t*t*t : 1-8*(--t)*t*t*t },
				easeInQuint: function (t) { return t*t*t*t*t },
				easeOutQuint: function (t) { return 1+(--t)*t*t*t*t },
				easeInOutQuint: function (t) { return t<.5 ? 16*t*t*t*t*t : 1+16*(--t)*t*t*t*t }
			};
			// ----------------------------------------- MathFunctions ----------------------------------------- //

			function initMap() {
				geoLoc = new GeoLoc();
				map = new google.maps.Map(document.getElementById('map-canvas'), {
					zoom: 18,
					center: {lat: 0, lng: 0},
					disableDefaultUI: true
				});
				markerControl = new MarkerControl([]);
				map.addListener('zoom_changed', function() {
					markerControl.view_distance_ = google.maps.geometry.spherical.computeDistanceBetween( map.getCenter(), map.getBounds().getNorthEast() );
				});
			}
			function refreshMap() {
				if(!init) {
					initMap();
					init = true;
				}
				centerControl = new CenterControl(document.getElementById('centerMap'));
			}
			var message = ['salut'];
			var geoLoc;
			var map;
			var centerControl;
			var markerControl;
			var init = false;




			/* Action jQuery mobile */
			var user = {email: '', password: ''};
			var panel = '#panelLogin';
			jQuery(document).on("pageshow","#map-page",function() {
				refreshMap();
			});
			jQuery(document).on("click","#signup_submit",function(event) {
				event.preventDefault();
				var data = {
					'page': 'utilisateur',
					'action': 'inscription',
					'email': jQuery("#email_signup").val(),
					'mot_de_passe': jQuery("#password_signup").val(),
				};
				jQuery.post(window.location.pathname+'/controleur/controleur.php', data, function(response) {
					if (response.success) {
						jQuery.mobile.changePage("#map-page");
					} else {
						for (i = 0; i < response.errors.length; i++) {
							var li = document.createElement("li");
							var notif = Math.random().toString(36).substr(2, 10);
							li.id = notif;
							li.appendChild(document.createTextNode(response.errors[i]));
							jQuery("#notifications_signup").append(li);
							setTimeout(function(){jQuery("#"+notif).hide(1000);setTimeout(function(){jQuery("#"+notif).remove();}, 1000)}, 5000);
						}
					}
				}, 'JSON');
			});
			jQuery(document).on("click","#login_submit",function(event) {
				event.preventDefault();
				var data = {
					'page': 'utilisateur',
					'action': 'connexion',
					'email': jQuery("#email_login").val(),
					'mot_de_passe': jQuery("#password_login").val(),
				};
				jQuery.post(window.location.pathname+'/controleur/controleur.php', data, function(response) {
					if (response.success) {
						jQuery.mobile.changePage("#map-page");
						user.email = data.email;
						user.password = data.mot_de_passe;
						panel = '#panelEvent';
						jQuery("#open_menu").attr("href", panel);
						jQuery("#eventBtn").attr("href", panel);
						jQuery("#panelLogin[data-role=panel]").panel("close");
					} else {
						for (i = 0; i < response.errors.length; i++) {
							var li = document.createElement("li");
							var notif = Math.random().toString(36).substr(2, 10);
							li.id = notif;
							li.appendChild(document.createTextNode(response.errors[i]));
							jQuery("#notifications_login").append(li);
							setTimeout(function(){jQuery("#"+notif).hide(1000);setTimeout(function(){jQuery("#"+notif).remove();}, 1000)}, 5000);
						}
					}
				}, 'JSON');
			});
			jQuery(document).on("click","#logout",function(event) {
				event.preventDefault();
				var data = {
					'page': 'utilisateur',
					'action': 'logout'
				};
				jQuery.post(window.location.pathname+'/controleur/controleur.php', data, function(response) {
					if (response.success) {
						jQuery.mobile.changePage("#map-page");
						panel = '#panelLogin';
						jQuery("#open_menu").attr("href", panel);
						jQuery("#eventBtn").attr("href", panel);
						jQuery("#panelEvent[data-role=panel]").panel("close");
					}
				}, 'JSON');
			});
		</script>
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
					<fieldset data-role="controlgroup">
						<?php
						$data = http_build_query(array('page' => 'type_incident', 'action' => 'findAll'));
						$options = array( 'http' => array( 'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen( $data ) . "\r\n", 'method'  => "POST", 'content' => $data ) );
						$url = 'http' . ((isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") ? 's' : '') . '://' . $_SERVER["SERVER_NAME"] . (($_SERVER["SERVER_PORT"] != "80") ? ':' . $_SERVER["SERVER_PORT"] : '') . ((substr($_SERVER['REQUEST_URI'], -1) == '/') ? dirname($_SERVER['REQUEST_URI']) : dirname(dirname($_SERVER['REQUEST_URI'])));
						$context = stream_context_create($options);
						$request = fopen($url . '/interface/controleur/controleur.php', 'r', false, $context);
						$response = json_decode( stream_get_contents( $request ) );
						if ($response->success) {
							foreach($response->data as $event_type) {
						?>
						<input type="radio" name="event-choice" id="event-choice-<?php echo $event_type->id; ?>" value="<?php echo $event_type->id; ?>">
						<label for="event-choice-<?php echo $event_type->id; ?>"><?php echo $event_type->nom; ?></label>
						<?php } } ?>
						<input type="radio" name="event-choice" id="event-choice-custom" value="custom_message" checked="checked">
						<label for="event-choice-custom"><textarea placeholder="Your custom mesage here..."></textarea></label>
					</fieldset>
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