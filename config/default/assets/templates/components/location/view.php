<div class='Component_Location_Title'>!{token://location_title}</div>
<div class='Component_Location_Subtitle'>!{token://location_subtitle}</div>
<div class='Component_Location_Street'>!{token://location_street}</div>
<div class='Component_Location_CityStateZip'>!{token://location_city}, !{token://location_state} !{token://location_zip}</div>
<div class='Component_Location_Phone'>!{token://location_phone}</div>

<div class='Component_Location_Button_Call show_phone intent_button'>
	<a href="tel://!{token://location_phone}" target="_blank"><button class="btn btn-info btn-block btn-large">Call Us</button></a>
</div>
<div class='Component_Location_Button_Directions show_phone intent_button'>
	<a href="http://maps.google.com/?q=!{token://location_url_encoded}" target="_blank"><button class="btn btn-info btn-block btn-large">Get Directions</button></a>
</div>