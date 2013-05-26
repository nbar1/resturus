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
	 * Returns client information array
	 * Also found in global $client
	 *
	 * return bool
	 */
	private function getClientInformation()
	{
		global $client;
		global $db;
		if(!isset($client['client_id']))
		{
			try
			{
				$request_url = preg_replace("/www\./", "", $_SERVER['SERVER_NAME']);

				$dbh = $db->prepare("SELECT * FROM clients WHERE client_portal=? LIMIT 1");
				$dbh->execute(array($request_url));
				$dbh->setFetchMode(PDO::FETCH_ASSOC);
				// set public $client to client information array
				$this->client = $dbh->fetch();
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
		else
		{
			return false;
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

	/**
	 * isUri
	 *
	 * @params string Uri
	 * @return bool
	 */
	public function isUri($uri)
	{
		if(preg_match("|^.+?://.+?/?$|", $uri))
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/**
	 * parseUri
	 *
	 *
	 *
	 */
	public function parseUri($uri)
	{
		if(preg_match("|^(.+?)://(.+?)/?$|", $uri, $uri_parsed))
		{
			return array('type' => $uri_parsed[1], 'path' => $uri_parsed[2]);
		}
		else
		{
			return false;
		}
	}
	
	public function remove_array_empty_values($array, $remove_null_number = true)
	{
		$new_array = array();
	
		$null_exceptions = array();
	
		foreach ($array as $key => $value)
		{
			$value = trim($value);
	
	        if($remove_null_number)
			{
		        $null_exceptions[] = '0';
			}
	
	        if(!in_array($value, $null_exceptions) && $value != "")
			{
	            $new_array[] = $value;
	        }
	    }
	    return $new_array;
	}
	
	public function parseComponent($config)
	{
		try
		{
			$component_config = @file_get_contents($config);
			if($component_config)
			{
				$component_config = trim(str_replace(array("<?php","<?","?>"), "", $component_config));
				return eval($component_config);
			}
			else
			{
				throw new brdi_Exception("Error loading component", 400);
			}
		}
		catch(brdi_Exception $e)
		{
			$e->logError();
			return false;
		}
	}
}
?>
