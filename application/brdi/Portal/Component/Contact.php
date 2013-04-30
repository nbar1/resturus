<?php
class brdi_Portal_Component_Contact extends brdi_Portal_Component
{
	public $location;

	public function getLocation()
	{
		if(!isset($this->location))
		{
			$this->setLocation();
		}
		return $this->location;
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
				$location = array();
				foreach($row as $k=>$v)
				{
					$location[str_replace("loc_" ,"" , $k)] = $v;
				}
				$this->location = $location;
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
?>