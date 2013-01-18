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
	public $config;
	public $type;

	/**
	 * build
	 *
	 * Builds component and returns data for Portal to render it
	 *
	 * @param Array $config Component configuration
	 * @return Array Assets and template for component
	 */
	public function build($config)
	{
		$this->config = $config[1]['config'];
		$this->type = $config[0];
		
		// get location_id from config or default
		$location_id = ($this->config['location_id'])?$this->config['location_id']:$this->getDefaultLocation();
		if($location_id)
		{
			$this->location = $this->getLocationFromDatabase($location_id);
		}

		// set component assets
		$this->setAllComponentJavascripts($this->config);
		$this->setAllComponentStylesheets($this->config);

		$template = $this->getComponentTemplate($config);

		$template = $this->parseToken($template, "token://location_title", $this->getLocationTitle());
		$template = $this->parseToken($template, "token://location_subtitle", $this->getLocationSubtitle());
		$template = $this->parseToken($template, "token://location_description", $this->getLocationDescription());
		$template = $this->parseToken($template, "token://location_hours", $this->getLocationHours());
		$template = $this->parseToken($template, "token://location_phone", $this->getLocationPhone());
		$template = $this->parseToken($template, "token://location_street", $this->getLocationStreet());
		$template = $this->parseToken($template, "token://location_city", $this->getLocationCity());
		$template = $this->parseToken($template, "token://location_state", $this->getLocationState());
		$template = $this->parseToken($template, "token://location_zip", $this->getLocationZip());

		return array(array($this->javascripts, $this->stylesheets), $template);
	}

	private function getLocationFromDatabase($location_id)
	{
		if(!$location_id) return false;

		$sql = "SELECT * FROM locations WHERE loc_id='".$location_id."' AND loc_client='".$this->getClientId."' AND loc_active='1' LIMIT 1";
		if($results = mysql_query($sql))
		{
			return mysql_fetch_assoc($results);
		}
		else {
			return false;
		}
	}
	
	private function getDefaultLocation()
	{
		$sql = "SELECT loc_id FROM locations WHERE loc_client='".$this->getClientId()."' AND loc_default='1' AND loc_active='1' LIMIT 1";
		if($results = mysql_query($sql))
		{
			$row = mysql_fetch_assoc($results);
			// set public $location_id as location id
			$this->location_id = $row['loc_id'];
		}
		else {
			return false;
		}
	}

	public function getLocationTitle()
	{
		return $this->location['loc_title'];
	}
	
	public function getLocationSubtitle()
	{
		return $this->location['loc_subtitle'];
	}
	
	public function getLocationDescription()
	{
		return $this->location['loc_description'];
	}
	
	public function getLocationHours()
	{
		return $this->location['loc_hours'];
	}
	
	public function getLocationPhone()
	{
		return $this->location['loc_contact_phone'];
	}
	
	public function getLocationStreet()
	{
		return $this->location['loc_contact_street'];
	}
	
	public function getLocationCity()
	{
		return $this->location['loc_contact_city'];
	}
	
	public function getLocationState()
	{
		return $this->location['loc_contact_state'];
	}
	
	public function getLocationZip()
	{
		return $this->location['loc_contact_zip'];
	}
}