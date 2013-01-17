<?php
$client_config = array(
	'client' => array(
		'id' => 1,
		'name' => 'Restur.us',
		'contact' => array(
			'phone' => '716-555-1235',
			'address' => array(
				'street'	=> '262 Ashland Ave',
				'city'		=> 'Buffalo',
				'state'		=> 'NY',
				'zip'		=> '14222',
			),
		),
		'locations' => array(
			array(
				'title' => 'Main Location',
				'phone' => '716-555-1234',
				'address' => array(
					'street'	=> '',
					'city'		=> 'Buffalo',
					'state'		=> 'NY',
					'zip'		=> '14222',
				),
				'hours' => array(
					0 => array(), //Sunday
					1 => array('11am', '11pm'), //Monday
					2 => array('11am', '11pm'), //Tuesday
					3 => array('11am', '11pm'), //Wednesday
					4 => array('11am', '11pm'), //Thursday
					5 => array('11am', '1am'), //Friday
					6 => array('11am', '1am'), //Saturday
				),
			),
		),
	),
	
	'pages' => array(
		array(
			'href' => '/',
			'type' => 'HomePage',
		),
		array(
			'title' => 'Menu',
			'href' => '/menu/',
			'class' => 'nav_menu',
			'nav' => 'default',
		),
		array(
			'title' => 'Specials',
			'href' => '/specials/',
			'class' => 'nav_specials',
			'nav' => 'default',
		),
		array(
			'title' => 'Location',
			'href' => '/locations/',
			'class' => 'nav_location',
			'nav' => 'default',
			'stylesheets' => array(
				'locations/locations.css',
			),
			'javascripts' => array(
				'http://maps.googleapis.com/maps/api/js?key=AIzaSyAt2NeyyKwxNeKGGC3MT2SrpQOs-AZr1dE&sensor=true',
				'locations/googlemapsapi.js',
			),
		),
		array(
			'title' => 'Order Online',
			'href' => '/orderonline/',
			'class' => 'nav_orderonline',
			'nav' => 'right',
		),
	),
);
?>