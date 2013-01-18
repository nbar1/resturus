<?php
$component_config = array(
	'type' => 'ImageSlider',
	'config' => array(
		'images' => array(
			'/assets/images/components/slider/1.jpg',
			'/assets/images/components/slider/3.jpg',
			'/assets/images/components/slider/4.jpg',
			'/assets/images/components/slider/5.jpg',
			'/assets/images/components/slider/6.jpg',
		),
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/slider/nivo-slider.css',
				'assets/stylesheets/components/slider/slider.css',
			),
			'javascripts' => array(
				'assets/javascripts/components/slider/jquery.nivo.slider.pack.js',
			),
		),
		'class' => 'hp_component slider-wrapper',
		'columns' => 12,
	),
);
?>