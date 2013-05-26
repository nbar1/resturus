<?php
return new brdi_Portal_Component_Tabbed(array(
	'tabs' => array(
		array(
			'title' => "Custom Renovations",
			'content' => "component://Tabbed/CustomRenovations/CustomRenovations/",
			'active' => true,
		),
		array(
			'title' => "Handicap Renovations",
			'content' => "component://RawHtml/CustomRenovations/HandicapRenovations/",
		),
		array(
			'title' => "Hurricane Preparedness",
			'content' => "component://RawHtml/CustomRenovations/HurricanePreparedness/",
		),
	),
));
?>