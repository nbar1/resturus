<?php
class brdi_Portal_Page extends brdi_Portal
{
	public $page_config;
	
	/**
	 * getPageConfigFromFile
	 */
	private function getPageConfigFromFile()
	{
		if(!$this->page_config)
		{
			$page = "page/".$this->getPagePath().".php";
			$page = $this->getConfigOverride($page);
			// include $page_config
			if($page)
			{
				require($page);
				$this->page_config = $page_config;
				return $this->page_config;
			}
			else {
				return false;
			}
		}
	}
	
	/**
	 * getPageConfig
	 */
	public function getPageConfig()
	{
		if(!$this->page_config)
		{
			return $this->getPageConfigFromFile();
		}
		else {
			return $this->page_config;
		}
	}
}
?>