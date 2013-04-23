<?php
return new brdi_Portal_Component_Nav(array(
	'nav' => array(
		'Home',
		'Aboutus',
		'Customrenovations',
		'Services',
		'Contact',
	),
	'links_only' => true,
	'assets' => array(
		'stylesheets' => array(
			'assets/stylesheets/components/nav/nav_links.css',
		),	
		'template' => 'template://components/nav/links/view/'
	),
));
?>