<?php
/**
 * brdi_Portal_Page
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Page extends brdi_Portal
{
	public $page_config;
	
	/**
	 * getPageConfigFromFile
	 *
	 *	Gets the current page config from 
	 *
	 * @return Array Page config
	 */
	public function getPageConfig()
	{
		if(!$this->page_config)
		{
			// get page config file
			$page = "page/".$this->getPagePath().".php";
			$page = $this->getConfigOverride($page);

			if($page)
			{
				// include $page_config
				require($page);
				// set local $page_config to public $page_config
				$this->page_config = $page_config;
				return $this->page_config;
			}
			else {
				return false;
			}
		}
		else {
			return $this->page_config;
		}
	}
}
?>