<?php
class brdi
{
	public $client;
	public $request;
	public $stylesheets;
	public $javascripts;

	function __construct()
	{
		// get client based on request url
		$request_url = mysql_real_escape_string(preg_replace("/www\./", "", $_SERVER['SERVER_NAME']));
		$sql = "SELECT * FROM clients WHERE client_portal='{$request_url}' LIMIT 1";
		try
		{
			$result = mysql_query($sql);
			$this->client = mysql_fetch_assoc($result);
		}
		catch(Exception $e) {
			echo "Caught Exception: " . $e->getMessage();
		}
		$this->request = $_SERVER['REQUEST_URI'];
		$this->stylesheets = array();
		$this->javascripts = array();
	}
	
	/**
	 * Returns the client id
	 *
	 * return Int
	 */
	public function getClientId()
	{
		return $this->client['client_id'];
	}
	
	/**
	 * Returns the client name
	 *
	 * return String
	 */
	public function getClientName()
	{
		return $this->client['client_name'];
	}
	
	/**
	 * Returns the client token
	 *
	 * return String
	 */
	public function getClientToken()
	{
		return $this->client['client_token'];
	}
	
	/**
	 * Returns client configuration
	 *
	 * return Array
	 */
	public function getClientConfiguration()
	{
			return $this->client;
	}
}
?>