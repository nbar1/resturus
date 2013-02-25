<?php
/**
 * brdi_Portal
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal extends brdi
{
	public $javascripts;
	public $stylesheets;

	/**
	 * getFileOverrides
	 *
	 * Checks for a client level override on config files
	 *
	 * @param String $path Path of config file
	 * @return String file path
	 */
	public function getConfigOverride($path)
	{
		if(!$path) return false;
		// check for client level override
		if(file_exists(CONFIG.$this->getClientToken()."/".$path))
		{
			return CONFIG.$this->getClientToken()."/".$path;
		}
		// check for default file
		elseif(file_exists(CONFIG."default/".$path))
		{
			return CONFIG."default/".$path;
		}
		// file not found
		else
		{
			error_log("No assets found at ".$path);
			return false;
		}
	}

	/**
	 * tokenize
	 *
	 * Performs token replacement
	 *
	 * @param Array $config Configuration for current page
	 * @return String Html of page to display
	 */
	public function tokenize($config)
	{
		global $assets;
		$content = $config['wrapper'];
		// parse page template
		$content = $this->parseToken($content, "template://internal", $config['template']);
		// parse all compnents on page
		$content = $this->parseAllComponents($content);
		// parse assets
		$content = $this->parseToken($content, "asset://stylesheets", $this->getAllStylesheets());
		$content = $this->parseToken($content, "asset://javascripts", $this->getAllJavascripts());
		// parse page id
		$content = $this->parseToken($content, "token://page", $config['pageid']);

		return $content;
	}

	/**
	 * parseToken
	 *
	 * Parses a given token against a given value
	 *
	 * @param String $data String with given token, raw data
	 * @param String $token Token to replace
	 * @param String $replace String to replace token with
	 * @return String Complete data
	 */
	public function parseToken($data, $token, $replace, $repeat = true)
	{
		if($repeat)
		{
			return preg_replace("|\!\{".preg_quote($token)."\}|", strtr($replace, array('\\' => '\\\\', '$' => '\$')), $data);
		}
		else {
			return preg_replace("|\!\{".preg_quote($token)."\}|", strtr($replace, array('\\' => '\\\\', '$' => '\$')), $data, 1);
		}
	}

	/**
	 * parseAllComponents
	 *
	 * Parse all components in the given template
	 *
	 * @param String $content Template with components
	 * @return String Template with components included
	 */
	public function parseAllComponents($content)
	{
		preg_match_all("|\!\{component\://([A-Za-z0-9-/_]+)\}|", $content, $components);
		foreach($components[1] as $component)
		{
			// get $component_config
			$config = $this->getConfigOverride("component/".strtolower($component).".php");
			include($config);

			if($component_config)
			{
				// build component class
				$comp_class = 'brdi_Portal_Component_'.$component_config['type'];
				$comp_builder = new $comp_class();
				// run build function
				$component_return = $comp_builder->build($component_config);
				//set component variables
				$component_html = $component_return[1];
				$component_assets = $component_return[0];
				//parse token against given content
				$content = $this->parseToken($content, "component://".$component, $component_html, false);
				unset($component_config);
			}
			else {
				$content = $this->parseToken($content, "component://".$component, "Error loading component: ".$component, false);
			}
		}
		return $content;
	}

	/**
	 * addJavascript
	 *
	 * Adds given javascript to $assets['javascripts']
	 *
	 * @param String $javascript File path to javascript file to load on page
	 * @return bool
	 */
	public function addJavascript($javascript)
	{
		global $assets;
		// check if file is local or remote
		if(substr($javascript, 0, 4) == 'http')
		{
			// add remote file
			array_push($assets['javascripts'], $javascript);
		}
		else {
			// add local file
			$js = $this->getConfigOverride($javascript);
			if($js) array_push($assets['javascripts'], $this->getConfigOverride($javascript));
		}
		return true;
	}

	/**
	 * addStylesheet
	 *
	 * Adds given stylesheet to $assets['stylesheets']
	 *
	 * @param String $stylesheet File path to stylesheet file to load on page
	 * @return bool
	 */
	public function addStylesheet($stylesheet)
	{
		global $assets;
		// check if file is locale or remote
		if(substr($stylesheet, 0, 4) == 'http')
		{
			// add remote file
			array_push($assets['stylesheets'], $stylesheet);
		}
		else {
			// add local file
			$css = $this->getConfigOverride($stylesheet);
			if($css) array_push($assets['stylesheets'], $this->getConfigOverride($stylesheet));
		}
		return true;
	}

	/**
	 * getAllJavascripts
	 *
	 * Get html for javascripts
	 *
	 * @return String Html formatted string of javascripts
	 *
	 */
	private function getAllJavascripts()
	{
		global $assets;
		// get all javascripts to include
		$javascripts = array_unique($assets['javascripts']);
		$html = "";
		foreach($javascripts as $js)
		{
			// parse javascript as html
			$html .= "<script type=\"text/javascript\" src=\"/".$js."\"></script>";
		}
		return $html;
	}

	/**
	 * getAllStylesheets
	 *
	 * Get html for stylesheets
	 *
	 * @return String Html formatted string of stylesheets
	 *
	 */
	private function getAllStylesheets()
	{
		global $assets;
		$css_files = array();
		// get all stylesheets to include
		$stylesheets = array_unique($assets['stylesheets']);
		foreach($stylesheets as $css)
		{
			array_push($css_files, $css);
		}
		// parse stylesheet as html
		$html = "<link rel=\"stylesheet\" type=\"text/stylesheet\" href=\"/brdi/scripts/css.php?load=".urlencode(json_encode($css_files))."\" />";
		return $html;
	}

	/**
	 * getPagePath
	 *
	 * Get page path
	 *
	 * @return String Page path
	 */
	public function getPagePath()
	{
		$page = strtolower(str_replace("/index.php","", $_SERVER['REQUEST_URI']));

		$page = explode("?",$page);
		$page = $page[0];
		// if blank, set as homepage
		if(!$page || $page == "/") $page = "home";

		// convert to path
		$page = (substr($page, -1) == "/")?substr($page, 0, -1):$page;
		$page = ($page[0] == "/")?substr($page, 1):$page;

		return $page;
	}

	/**
	 * getPageHref
	 *
	 * Returns the href of the current page
	 *
	 * @return string Href of page
	 */
	public function getPageHref()
	{
		return strtolower(str_replace("/","",$_SERVER['REQUEST_URI']));
	}

	/**
	 * getPageRoot
	 *
	 * Returns the root href of the current page
	 *
	 * @return string Root href of page
	 */
	public function getPageRoot($url = false)
	{
		if(!$url) $url = $_SERVER['REQUEST_URI'];
		$requesturl = explode("/", $url);
		if(strlen($requesturl[0]) < 1)
		{
			$requesturl = $requesturl[1];
		}
		else {
			$requesturl = $requesturl[0];
		}
		return strtolower($requesturl);
	}

	/**
	 * isThisPage
	 *
	 * Returns wether the current page is the same Href as the passed in Href
	 *
	 * @return bool
	 */
	public function isThisPage($config_page)
	{
		if($this->getPageRoot() == $this->getPageRoot($config_page)) return true;
		else return false;
	}

	/**
	 * getPageParent
	 *
	 * Returns the scope of the parent page
	 *
	 * @return string Parent page
	 */
	public function getPageParent($page)
	{
		if($page[0] == "/")
		{
			$page = substr($page, 1);
		}
		$page = explode("/", $page);
		$parent = $page[0];
		if(!$parent) $parent = "homepage";
		return $parent;
	}
}
?>
