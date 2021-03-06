<?php
/**
 * brdi_Portal_Page
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Page extends brdi_Portal
{
	protected $_page_config;

	/**
	 * getPageConfigFromFile
	 *
	 *	Gets the current page config from 
	 *
	 * @return Array Page config
	 */
	public function getPageConfig()
	{
		if(!isset($this->_page_config))
		{
			try
			{
				$page = $this->isValidPage($this->getPagePath());
				if($page === false || $page === null)
				{
					header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
					header("Status: 404 Not Found");
					$_SERVER['REDIRECT_STATUS'] = 404;
					$page = $this->getConfigOverride("Page/Error/404/page_config.php");
				}

				// include $page_config
				require($page);
				if(!isset($page_config['assets']['javascripts'])) $page_config['assets']['javascripts'] = array();
				if(!isset($page_config['assets']['stylesheets'])) $page_config['assets']['stylesheets'] = array();
				if(!isset($page_config['wrapper'])) $page_config['wrapper'] = "template://wrappers/default/";
				$page_config['wrapper'] = $this->getTemplate($page_config['wrapper']);
				if(!isset($page_config['assets']['template'])) 
				{
					$page_config['assets']['template'] = $this->getTemplate("template://pages/home/");
				}
				else
				{
					$page_config['assets']['template'] = $this->getTemplate($page_config['assets']['template']);
				}
				$page_config['pageid'] = str_replace("/", "_", $this->getPagePath());
				
				$this->_page_config = $page_config;
				return $this->_page_config;
			}
			catch(brdi_Exception $e)
			{
				$e->logError();
				return false;
			}
		}
		else {
			return $this->_page_config;
		}
	}
	
	private function isValidPage($page)
	{
		$pages_file = $this->getConfigOverride("Page/pages.php");
		if($pages_file && file_exists($pages_file))
		{
			require($pages_file);
			
			$page = explode("/",$page);
			$page = array_map('strtolower', $page);
			$pageUri = $this->getContentValue($page, $pages);
			
			if(is_array($pageUri) && isset($pageUri['_']))
			{
				$pageUri = $pageUri['_'];
			}
			
			if($pageUri === false) return false;
			
			$pageUri = $this->parseUri($pageUri);

			$page = "Page/".$pageUri['path']."/page_config.php";
			$page = $this->getConfigOverride($page);
			return $page;
			
		}
		else
		{
			return false;
		}
	}

	/**
	 * getPagePath
	 *
	 * Get page path
	 *
	 * @return String Page path
	 */
	public function getPagePath($checkcms=true)
	{
		$page = strtolower(str_replace("/index.php","", $_SERVER['REQUEST_URI']));

		$page = explode("?",$page);
		$page = $page[0];
		// if blank, set as homepage
		if(!$page || $page == "/") $page = "_";

		$page = explode("/", $page);
		foreach($page as $k=>$v) $page[$k] = ucfirst($v);
		$page = implode("/", $page);

		// convert to path
		$page = (substr($page, -1) == "/")?substr($page, 0, -1):$page;
		$page = ($page[0] == "/")?substr($page, 1):$page;
		return $page;
	}

	/**
	 * setAllPageJavascripts
	 *
	 * Sets up all the js files from the page config
	 *
	 * @return bool
	 */
	public function setAllPageJavascripts()
	{
		$config = $this->getPageConfig();
		if(is_array($config['assets']['javascripts']))
		{
			foreach($config['assets']['javascripts'] as $js)
			{
				$this->addJavascript($js);
			}
		}				
		return true;
	}

	/**
	 * setAllPageStylesheets
	 *
	 * Sets up all the js files from the page config
	 *
	 * @return bool
	 */
	public function setAllPageStylesheets()
	{
		$config = $this->getPageConfig();
		if(is_array($config['assets']['javascripts']))
		{
			foreach($config['assets']['stylesheets'] as $css)
			{
				$this->addStylesheet($css);
			}
		}
		return true;
	}
}
?>