<?php
class brdi_Api_Rest extends brdi_Api
{
	function __construct($method, $url, $postvars = array())
	{
		switch($method)
		{
			case 'GET':
				return $this->doRequestGet($url);
			break;
			case 'POST':
			
			break;
			case 'PUT':
			
			break;
			case 'DELETE':
			
			break;
			default:
				return false;
			break;
		}
	}
	
	private function doRequestGet($url)
	{
		$ch= curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
		$result = curl_exec($ch);
		var_dump($result);
		curl_close($ch);
		return $result;
	}
	
	private function doRequestPost(string $url, array $postvars)
	{
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_________string);
		$result = curl_exec($ch);
		curl_close($ch);
		
	}
	
	private function doRequestPut(string $url, array $postvars)
	{
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		$result = curl_exec($ch);
		curl_close($ch);
		
	}
	
	private function doRequestDelete(string $url, array $postvars)
	{
		$ch=curl_init($url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
		$result = curl_exec($ch);
		curl_close($ch);
		
	}
}
?>