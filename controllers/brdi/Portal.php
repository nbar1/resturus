<?php
/**
 * brdi_Portal
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal extends brdi
{
	public $stylesheets;
	public $javascripts;
	public $request;

	/**
	 * construct
	 *
	 * Gets client information from database on each pageload
	 */
	function __construct()
	{
		// get client based on request url
		$request_url = mysql_real_escape_string(preg_replace("/www\./", "", $_SERVER['SERVER_NAME']));
		$sql = "SELECT * FROM clients WHERE client_portal='{$request_url}' LIMIT 1";
		try
		{
			$result = mysql_query($sql);
			// set public $client to client information array
			$this->client = mysql_fetch_assoc($result);
		}
		catch(Exception $e) {
			echo "Caught Exception: " . $e->getMessage();
		}
		// set public $request to request uri
		$this->request = $_SERVER['REQUEST_URI'];
		// initialize assets arrays
		$this->stylesheets = array();
		$this->javascripts = array();
	}

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
			return preg_replace("|\!\{".preg_quote($token)."\}|", $replace, $data);
		}
		else {
			return preg_replace("|\!\{".preg_quote($token)."\}|", $replace, $data, 1);
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
	private function parseAllComponents($content)
	{
		preg_match_all("|\!\{component\://(\w+)\}|", $content, $components);
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
				$component_return = $comp_builder->build(array($component, $component_config));
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
		// get all stylesheets to include
		$stylesheets = array_unique($assets['stylesheets']);
		$html = "";
		foreach($stylesheets as $css)
		{
			// parse stylesheet as html
			$html .= "<link rel=\"stylesheet\" href=\"/".$css."\" />";
		}
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
		return strtolower(str_replace("/","",$this->request));
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
		if(strtolower(str_replace("/","",$this->request)) == strtolower(str_replace("/","",$config_page))) return true;
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