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
	
	/**
	 * Returns the client id
	 *
	 * @return Int
	 */
	public function getClientId()
	{
		return $this->client['client_id'];
	}
	
	/**
	 * Returns the client name
	 *
	 * @return String
	 */
	public function getClientName()
	{
		return $this->client['client_name'];
	}
	
	/**
	 * Returns the client token
	 *
	 * @return String
	 */
	public function getClientToken()
	{
		return $this->client['client_token'];
	}
	
	/**
	 * Returns client configuration
	 *
	 * @return Array
	 */
	public function getClientConfiguration()
	{
			return $this->client;
	}
}
?>