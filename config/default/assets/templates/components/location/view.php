<div class='Component_Location_Title'>!{token://location/title/}</div>
<div class='Component_Location_Subtitle'>!{token://location/subtitle/}</div>
<div class='Component_Location_Street'>!{token://location/street/}</div>
<div class='Component_Location_CityStateZip'>!{token://location/city/}, !{token://location/state/} !{token://location/zip/}</div>
<div class='Component_Location_Phone'>!{token://location/phone/}</div>

<div class='Component_Location_Button_Call visible-phone intent_button'>
	<a href="tel://!{token://location/phone/}" target="_blank"><button class="btn btn-block btn-large">Call Us</button></a>
</div>
<div class='Component_Location_Button_Directions !{token://maps_always_visible} intent_button'>
	<a href="http://maps.google.com/?q=!{token://location/urlencoded/}" target="_blank"><button class="btn btn-block btn-large">Get Directions</button></a>
</div>