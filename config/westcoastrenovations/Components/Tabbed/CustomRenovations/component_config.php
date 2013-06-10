<?php
return new brdi_Portal_Component_Tabbed(array(
	'tabs' => array(
		array(
			'title' => "Custom Renovations",
			'content' => "!{component://RawHtml/CustomRenovations/CustomRenovations/}",
			'active' => true,
		),
		array(
			'title' => "Handicap Renovations",
			'content' => "!{component://RawHtml/CustomRenovations/HandicapRenovations/}",
			'active' => false,
		),
		array(
			'title' => "Hurricane Preparedness",
			'content' => "!{component://RawHtml/CustomRenovations/HurricanePreparedness/}",
			'active' => false,
		),
	),
));
?>