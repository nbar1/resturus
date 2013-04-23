<?php
/**
 * brdi_Portal_Component_Location
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Location extends brdi_Portal_Component
{
	public $location;
	
	protected $_params = array(
		'show_call_button' => true,
		'show_maps_button' => true,
		'maps_always_visible' => false,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/location/location.css',
			),
			'template' => 'template://components/location/view/',
		),
		'show_component_title' => true,
		'component_title' => "Location",
		'columns' => 4,
	);

	/**
	 * build
	 *
	 * Builds component and returns data for Portal to render it
	 *
	 * @param Array $config Component configuration
	 * @return Array Assets and template for component
	 */
	public function actionDefault()
	{
		$params = $this->getParams();
		
		$this->setLocation();
		
		$content = array(
			'location' => array(
				'title' => $this->location['loc_title'],
				'subtitle' => $this->location['loc_subtitle'],
				'description' => $this->location['loc_description'],
				'hours' => $this->location['loc_hours'],
				'phone' => $this->location['loc_contact_phone'],
				'street' => $this->location['loc_contact_street'],
				'city' => $this->location['loc_contact_city'],
				'state' => $this->location['loc_contact_state'],
				'zip' => $this->location['loc_contact_zip'],
				'urlencoded' => urlencode($this->location['loc_contact_street']." ".$this->location['loc_contact_city']." ".$this->location['loc_contact_state']." ".$this->location['loc_contact_zip']),
			),
			'maps_always_visible' => 'visible-phone',
		);
		
		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
	
	private function setLocation()
	{
		$params = $this->getParams();

		$location_id = (isset($params['location_id']))?$params['location_id']:$this->getDefaultLocation();
		if($location_id)
		{
			$this->location = $this->getLocationFromDatabase($location_id);
		}
	}

	public function getLocation()
	{
		return $this->location;
	}

	private function getLocationFromDatabase($location_id)
	{
		global $db;
		if(!$location_id) return false;

		try
		{
			$data = array($location_id, $this->getClientId(), 1);
			$dbh = $db->prepare("SELECT * FROM locations WHERE loc_id=? AND loc_client=? AND loc_active=? LIMIT 1");
			$dbh->execute($data);
			$dbh->setFetchMode(PDO::FETCH_ASSOC);
			$row = $dbh->fetch();	
			if($row)
			{
				$this->location = $row;
				return $this->location;
			}
			else
			{
				Throw new brdi_Exception("Error getting default location");
				return false;
			}
		}
		catch(brdi_Exception $e) {
			$e->logError();
			return false;
		}
	}
	
	private function getDefaultLocation()
	{
		global $db;
		try
		{
			$data = array($this->getClientId(), 1, 1);
			$dbh = $db->prepare("SELECT loc_id FROM locations WHERE loc_client=? AND loc_default=? AND loc_active=? LIMIT 1");
			$dbh->execute($data);
			$dbh->setFetchMode(PDO::FETCH_ASSOC);				
			$row = $dbh->fetch();
			if($row)
			{
				return $row['loc_id'];
			}
			else
			{
				Throw new brdi_Exception("Error getting default location");
			}
		}
		catch(brdi_Exception $e) {
			$e->logError();
			return false;
		}
	}
}