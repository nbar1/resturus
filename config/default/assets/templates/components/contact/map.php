<div id="map_container">
	<div id="map-canvas" class="hidden-phone"></div>
	!{if://show_location_over_map/}
		<div id="map_location_overlay">
			<h1>Contact Us</h1>
			<h3 class="contact_title ct_phone">Phone</h3>
			<h3 class="phonenumber">!{token://location/contact_phone/}</h3>
			<br />
			<h3 class="contact_title">Mailing Address</h3>
			<h3>!{token://location/contact_street/}</h3>
			<h3>!{token://location/contact_city/}, !{token://location/contact_state/} !{token://location/contact_zip/}</h3>
		</div>
	!{endif://show_location_over_map/}
	<script type="text/javascript">
	function initialize() {
		var styles = [{ featureType: "poi.business", elementType: "labels", stylers: [ { visibility: "off" } ] },{stylers: [{ saturation: -100 }]}];
		var styledMap = new google.maps.StyledMapType(styles,{name: "BWMap"});
		var mapOptions = {
			center: new google.maps.LatLng(!{token://map/lat/}, !{token://map/long/}),
			zoom: !{token://zoom/},
			mapTypeControlOptions: {
				mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']
			},
			disableDefaultUI: true,
			scrollwheel: false,
			navigationControl: false,
			mapTypeControl: false,
			scaleControl: false,
			draggable: false,
			disableDoubleClickZoom: true,
		};
	
		map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
		map.mapTypes.set('map_style', styledMap);
		map.setMapTypeId('map_style');
		
		var marker = new google.maps.Marker({
		    position: new google.maps.LatLng(!{token://location/lat/}, !{token://location/long/})
		});
		marker.setMap(map);
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	</script>
</div>