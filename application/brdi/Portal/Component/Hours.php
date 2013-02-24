<?php
class brdi_Page_Component_Hours extends brdi_Page_Component
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
		if($this->hours <> false)
		{
			echo "<div class='".$config['config']['class']."'>";
			echo "<div class='Component_Location_Title'>Hours</div>";
			echo "<div class='Component_Location_Phone'>".$this->getLocationHours()."</div>";
			echo "</div>";
		}	
	}
	
	public function getLocationHours()
	{
		return $this->hours['loc_hours'];
	}
}