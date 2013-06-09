<?php
return new brdi_Portal_Component_Nav(array(
	'show_title' => false,
	'nav' => array(
		'AboutUs',
		'CustomRenovations',
		'Services',
		'Contact',
	),
	'centered' => true,
	'assets' => array(
		'stylesheets' => array(
			'assets/stylesheets/components/nav/nav_centered.css',
		),
		'template' => 'template://components/nav/default/',
	),
));
?>