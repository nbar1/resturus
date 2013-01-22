<?php
/**
 * brdi
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi
{
	public $client;
	public $request;
	public $stylesheets;
	public $javascripts;
	
	private function getClientInformation()
	{
		global $client;
		if(!isset($client['client_id']))
		{
			// get client based on request url
			$request_url = mysql_real_escape_string(preg_replace("/www\./", "", $_SERVER['SERVER_NAME']));
			$sql = "SELECT * FROM clients WHERE client_portal='" . $request_url . "' LIMIT 1";
			try
			{
				$result = mysql_query($sql);
				// set public $client to client information array
				$this->client = mysql_fetch_assoc($result);
				// set global
				$client = $this->client;

				return true;
			}
			catch(Exception $e) {
				echo "Caught Exception: " . $e->getMessage();

				return false;
			}
		}
		else {
			return true;
		}
	}
	/**
	 * Returns the client id
	 *
	 * @return Int
	 */
	public function getClientId()
	{
		global $client;
		if(isset($client['client_id']))
		{
			return $client['client_id'];
		}
		else {
			if($this->getClientInformation())
			{
				return $this->client['client_id'];
			}
			else {
				return false;
			}
		}
	}
	
	/**
	 * Returns the client name
	 *
	 * @return String
	 */
	public function getClientName()
	{
		global $client;
		if($this->getClientId())
		{
			return $client['client_name'];
		}
	}
	
	/**
	 * Returns the client token
	 *
	 * @return String
	 */
	public function getClientToken()
	{
		global $client;
		if($this->getClientId())
		{
			return $client['client_token'];
		}
	}
	
	/**
	 * Returns client configuration
	 *
	 * @return Array
	 */
	public function getClientConfiguration()
	{
		global $client;
		if($this->getClientId())
		{
			return $client;
		}
	}
	
	/**
	 * getRequestUri
	 *
	 * Returns the href of the current page
	 *
	 * @return string Href of page
	 */
	public function getRequestUri()
	{
		return $_SERVER['REQUEST_URI'];
	}
}
?>