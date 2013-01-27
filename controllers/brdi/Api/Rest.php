<?php
class brdi_Api_Rest extends brdi_Api
{
	public $curl_opts;
	public $return_data;

	function __construct($curl_opts, $return_data = false)
	{
		$this->curl_opts = $curl_opts;
		$this->return_data = $return_data;
	}

	public function initialize()
	{
		if(!is_array($this->curl_opts) || empty($this->curl_opts))
		{
			echo "error";
			//Throw new brdi_Exception();
		}
		else {
			try {
				$ch = curl_init();
				curl_setopt_array($ch, $this->curl_opts);
				$result = curl_exec($ch);
				curl_close($ch);
				if($this->return_data === TRUE)
				{
					if(is_array(json_decode($result, true)))
					{
						return json_decode($result, true);
					}
					else {
						return $result;
					}
				}
			}
			catch (Exception $e) {
				echo "exception: ". $e->getMessage;
				return false;
			}
		}
	}
}
?>