			// ----------------------------------------- CenterControl ----------------------------------------- //
			function CenterControl(centerDiv) {
				var control = this;
				control.center_ = {lat: 0, lng: 0};

				function load_events() {
						old_view_center_ = control.view_center_;
						control.view_center_ = map.getCenter();
						var data = {
							'page': 'incident',
							'action': 'findInArea',
							'latitude': map.getCenter().lat,
							'longitude': map.getCenter().lng,
							'rayon': control.radius_,
							'token': localStorage.getItem('token')
						};
						jQuery.post(window.location.pathname+'/controleur/controleur.php', data, function(response) {
							if (response.success) {
								console.log(response.data);
							} else {
								control.view_center_ = old_view_center_;
							}
						}, 'JSON');
				}

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
					if(!!control.view_center_ && google.maps.geometry.spherical.computeDistanceBetween(map.getCenter(), control.view_center_) > (control.radius_ - (control.radius_ / control.view_distance_))) {
						load_events();
					}
				});
				google.maps.event.addListener(map, 'zoom_changed', function() {
					control.radius_ = google.maps.geometry.spherical.computeDistanceBetween(map.getCenter(), map.getBounds().getNorthEast()) * control.view_distance_;
				});
				google.maps.event.addListenerOnce(map, 'idle', function(){
					control.radius_ = google.maps.geometry.spherical.computeDistanceBetween(map.getCenter(), map.getBounds().getNorthEast()) * control.view_distance_;
					load_events();
				});

				jQuery(document).on("click","#open_menu", function() {
					jQuery("#event-latitude").val(control.center_.lat);
					jQuery("#event-longitude").val(control.center_.lng);
				});
			}
			CenterControl.prototype.goCenterUI_ = null;
			CenterControl.prototype.center_ = null;
			CenterControl.prototype.radius_ = null;
			CenterControl.prototype.geoLocMarker_ = null;
			CenterControl.prototype.followCenter_ = true;
			CenterControl.prototype.view_distance_ = 2;
			CenterControl.prototype.view_center_ = null;
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
					//control.addMarker(position.latLng.lat(),position.latLng.lng(),control.markerControlDiv());
					jQuery("#event-latitude").val(position.latLng.lat());
					jQuery("#event-longitude").val(position.latLng.lng());
					jQuery(panel).panel('open');
				});
				map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(control.markerControlDiv());
			}
			MarkerControl.prototype.markerList_ = [];
			MarkerControl.prototype.addMarker = function(lat,lng,content) {
				var addMarker = this;
				var newMarker = new google.maps.Circle({
					strokeColor: '#FF0000',
					strokeOpacity: 0.15,
					strokeWeight: 2,
					fillColor: '#FF0000',
					fillOpacity: 0.05,
					map: map,
					center: {lat: lat, lng: lng},
					radius: 100,
	    			draggable: false
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
				return {marker:newMarker,markerInfo:newMarkerInfo,index:addMarker.markerList_.length-1};
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
					//markerControlDiv_.addMarker(centerControl.center_.lat,centerControl.center_.lng,markerControlDiv_.markerControlDiv());
					jQuery("#event-latitude").val(centerControl.center_.lat);
					jQuery("#event-longitude").val(centerControl.center_.lng);
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
			if(localStorage.getItem('token')==null) {
				var panel = '#panelLogin';
			} else {
				var panel = '#panelEvent';
				jQuery(document).ready(function() {
					jQuery("#open_menu").attr("href", panel);
					jQuery("#eventBtn").attr("href", panel);
				});
			}
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
						localStorage.setItem('token',response.data.token);
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
						localStorage.setItem('token',response.data.token);
						jQuery.mobile.changePage("#map-page");
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
					'action': 'logout',
					'token': localStorage.getItem('token')
				};
				jQuery.post(window.location.pathname+'/controleur/controleur.php', data, function(response) {
					if (response.success) {
						localStorage.clear();
						jQuery.mobile.changePage("#map-page");
						panel = '#panelLogin';
						jQuery("#open_menu").attr("href", panel);
						jQuery("#eventBtn").attr("href", panel);
						jQuery("#panelEvent[data-role=panel]").panel("close");
					}
				}, 'JSON');
			});
			jQuery(document).on("click","#event_send_submit",function(event) {
				event.preventDefault();
				function errorsFcn(response, marker) {
					for (i = 0; i < response.errors.length; i++) {
						if (typeof marker != 'undefined') {
							marker.marker.setMap(null);
							marker.markerInfo.close();
							markerControl.markerList_.splice(marker.index, 1);
						}
						var li = document.createElement("li");
						var notif = Math.random().toString(36).substr(2, 10);
						li.id = notif;
						li.appendChild(document.createTextNode(response.errors[i]));
						jQuery("#notifications_event").append(li);
						setTimeout(function(){jQuery("#"+notif).hide(1000);setTimeout(function(){jQuery("#"+notif).remove();}, 1000)}, 5000);
					}
				}
				var content = '';
				var response = {};
				response.errors = [];
				var event_type = jQuery("input[type='radio'][name='event-choice']:checked").val();
				var custom_message = '';
				if (event_type == 'custom_message') {
					event_type = null;
					custom_message = jQuery("#event-choice-custom-textarea").val();
					content = custom_message;
					if (custom_message == '') {
						response.errors.push('Message manquant');
					}
				} else {
					content = jQuery('label[for="event-choice-'+event_type+'"]').html();
				}
				if (response.errors.length == 0) {
					var lat = parseFloat(jQuery("#event-latitude").val());
					var lng = parseFloat(jQuery("#event-longitude").val());
					var marker = markerControl.addMarker(lat,lng,content);
					var data = {
						'page': 'incident',
						'action': 'save',
						'latitude': lat,
						'longitude': lng,
						'custom_incident': custom_message,
						'type_incident_id': event_type,
						'token': localStorage.getItem('token')
					};
					jQuery.post(window.location.pathname+'/controleur/controleur.php', data, function(response) {
						if (response.success) {
							jQuery("#panelEvent[data-role=panel]").panel("close");
							jQuery("#event-choice-custom-textarea").val('');
							jQuery(document).find("input[name='event-choice']").prop("checked", false).checkboxradio('refresh');
							jQuery(document).find("#event-choice-custom").prop("checked", true).checkboxradio('refresh');
						} else {
							errorsFcn(response, marker);
						}
					}, 'JSON');
				} else {
					errorsFcn(response);
				}
			});