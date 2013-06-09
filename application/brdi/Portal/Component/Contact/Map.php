<?php
class brdi_Portal_Component_Contact_Map extends brdi_Portal_Component_Contact
{
	protected $_params = array(
		'address' => "PO Box 2316 33932",
		'show_location_over_map' => true,
		'zoom' => 14,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/contact/map.css',
				'assets/stylesheets/components/contact/map_override.css',
			),
			'javascripts' => array(
				'https://maps.googleapis.com/maps/api/js?key=AIzaSyBeI7bxg1ex7zh7w34ABPIYiX2CSXjJYWY&sensor=false',
			),	
			'template' => 'template://components/contact/map/',
		),
		'columns' => 12,
		'wrapper' => "template://wrappers/component_bare/",
	);

	public function actionDefault()
	{
		$params = $this->getParams();
		
		$latLong = $this->getAddressLatLong($params['address']);

		$params['map']['lat'] = $latLong[0];
		$params['map']['long'] = $this->stableLongAtZoom($latLong[1], $params['zoom']);

		$params['location'] = $this->getLocation();
		$params['location']['lat'] = $latLong[0];
		$params['location']['long'] = $latLong[1];
		
		$this->setContent($params);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
	
	private function getAddressLatLong($address)
	{
		$address = str_replace(" ", "+", $address);

		$json = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=$address&sensor=false");
		$json = json_decode($json);
		
		$lat = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
		$long = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		
		return array($lat, $long);
	}
	
	private function stableLongAtZoom($long, $zoom)
	{
		switch($zoom)
		{
			case 13:
			case 14:
			default:
				$long = $long + 0.02;
			break;
		}
		return $long;
	}
}
?>