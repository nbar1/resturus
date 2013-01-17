<?php
class brdi_Page_Component_Location extends brdi_Page_Component
{
	public $loc;
	public $usable;
	
	public function __construct($config)
	{
		$sql = "SELECT * FROM locations WHERE loc_id='".$config['config']['location_id']."' AND loc_client='".$this->getClientId()."' AND loc_active='1' LIMIT 1";
		if($results = mysql_query($sql))
		{
			$this->loc = mysql_fetch_assoc($results);
			$this->usable = true;
		}
		else {
			$this->loc = false;
			$this->usable = false;
		}
	}
	
	public function isUsable()
	{
		return $this->usable;
	}
	
	public function actionBuild($config)
	{
		if($this->loc <> false)
		{
			echo "<div class='".$config['config']['class']."'>";
			echo "<div class='Component_Location_Title'>".$this->getLocationTitle()."</div>";
			if($this->getLocationSubtitle()) echo "<div class='Component_Location_Subtitle'>".$this->getLocationSubtitle()."</div>";
			echo "<div class='Component_Location_Street'>".$this->getLocationStreet()."</div>";
			echo "<div class='Component_Location_CityStateZip'>".$this->getLocationCity().", ".$this->getLocationState()." " . $this->getLocationZip()."</div>";
			echo "<div class='Component_Location_Phone'>".$this->getLocationPhone()."</div>";
			echo "</div>";
		}	
	}
	
	public function getLocationTitle()
	{
		return $this->loc['loc_title'];
	}
	
	public function getLocationSubtitle()
	{
		return $this->loc['loc_subtitle'];
	}
	
	public function getLocationDescription()
	{
		return $this->loc['loc_description'];
	}
	
	public function getLocationHours()
	{
		return $this->loc['loc_hours'];
	}
	
	public function getLocationPhone()
	{
		return $this->loc['loc_contact_phone'];
	}
	
	public function getLocationStreet()
	{
		return $this->loc['loc_contact_street'];
	}
	
	public function getLocationCity()
	{
		return $this->loc['loc_contact_city'];
	}
	
	public function getLocationState()
	{
		return $this->loc['loc_contact_state'];
	}
	
	public function getLocationZip()
	{
		return $this->loc['loc_contact_zip'];
	}
}