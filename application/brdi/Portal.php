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
		if(strstr($path, "cmsimages/") !== false) return $path;
		try
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
		}
		catch (brdi_Exception $e)
		{
			echo $path;
			Throw new brdi_Exception(301, "", $this);
			return false;
		}
	}
	
	/**
	 * getTokens
	 *
	 * @param String $template Template file to search
	 * @return Array tokens, types, and paths
	 */
	private function getTokens($template)
	{
		preg_match_all("|!{(.+?)://([A-Za-z0-9/_\.\]\[ ]+)/?}|", $template, $matches);
		foreach($matches[2] as $k=>$v)
		{
			$matches[2][$k] = explode("/", $v);
		}
		return $matches;
	}
	
	/**
	 * renderTokens
	 *
	 * Parses all tokens
	 *
	 * @param String $template Template file to search
	 * @param Array $content Content that will be replacing tokens
	 * @param Array $config Config array, might be able to phase this out
	 * @return String Finalized template
	 */
	public function renderTokens($template, $content = array(), $config = array())
	{
		$tokens = $this->getTokens($template);
		foreach($tokens[1] as $k=>$type)
		{
			$raw_token = $tokens[0][$k];
			$params = $tokens[2][$k];

			// check for variable in token and continue if present
			if(strstr($raw_token, "[*]") !== false) continue;

			switch($type)
			{
				case "component":
					$component = implode("/", array_filter($params));
					if(strtolower($component) == "pageaction")
					{
						$template = $this->loadCmsComponent(array(
							'template' => $template,
							'content' => $content,
							'config' => $config,
							'rawtoken' => $raw_token,
							'tokenparams' => $params,
						));					}
					else
					{
						$config = $this->getConfigOverride("component/".strtolower($component)."/component.php");
						if($config)
						{
							try
							{
								include($config);
					
								if($component_config)
								{
									// build component class
									$comp_class = 'brdi_Portal_Component_'.$component_config['type'];
									$comp_builder = new $comp_class();
									// run build function
									$component_return = $comp_builder->build($component_config);
									//set component variables
									$component_assets = $component_return[0];
									$component_html = $this->renderTokens($component_return[1], $component_return[2], $component_return[3]);
									//parse token against given content
									$template = $this->replaceToken($template, $raw_token, $component_html, false);
									unset($component_config);
								}
								else {
									$template = $this->replaceToken($template, $raw_token, "Error loading component: ".$component, false);
								}
							}
							catch (brdi_Exception $e)
							{
								Throw new brdi_Exception(401, null, $this);
							}
						}
						else
						{
							$template = $this->replaceToken($template, $raw_token, "Error loading component: ".$component, false);
						}
					}
				break;

				case "html":
					$template = $this->replaceToken($template, $raw_token, $this->getContent($params, $content));
				break;

				case "if";
					if($this->getContent($params[0], $content) === true)
					{
						$this->replaceToken($template, $raw_token, "");
						$this->replaceToken($template, "!{endif://{$params[0]}/}", "");
					}
					else {
						$delif = explode("!{if://{$params[0]}/}", $template, 1);
						$delendif = explode("!{endif://{$params[0]}/}", $template, 1);
						$template = $delif[0].$delendif[1];
					}
				break;
				
				case "loop":
					$loop = implode("/", array_filter($params));
					$loopdata = $this->getContent($params[0], $content);
					if(is_array($loopdata))
					{
						$count = sizeof($loopdata);
						preg_match("|!{loop://".$loop."/?}(.+?)!{endloop://".$loop."/?}|s", $template, $loop_content);
						$loop_content = trim($loop_content[1]);
						$loop_template = "";
						if($params[0] == "images") sort($content[$params[0]]);
						for($i=0; $i<$count; $i++)
						{
							$loop_template .= $this->renderTokens(str_replace("[*]", $i, $loop_content), $content, $config);
						}
					}
					$template = preg_replace("|!{loop://".$loop."/?}(.+?)!{endloop://".$loop."/?}|s", $loop_template, $template, 1);
				break;
				
				case "loopvar":
					$template = $this->replaceToken($template, $raw_token, $this->getContent($params, $content));
				break;

				case "image":
					$params = array_filter($params);
					$image_class = array_pop($params);
					if(strstr($image_class, ".")) 
					{
						array_push($params, $image_class);
						$image_class = "";
					}
					$params = implode("/", $params);
					$src = $this->getConfigOverride($params);
					if($src) $template = $this->replaceToken($template, $raw_token, "<img src='/{$src}' class='{$image_class}' />");
				break;

				case "token":
					$template = $this->replaceToken($template, $raw_token, $this->getContent($params, $content));
				break;
			}
		}
		return $template;
	}
	
	/**
	 * loadCmsComponent
	 *
	 *
	 *
	 *
	 */
	private function loadCmsComponent($params)
	{
		// build component class
		$cms_component = array_filter(explode("/", $this->getPagePath(false)));
		foreach($cms_component as $k=>$v) $cms_component[$k] = ucfirst($v);
		
		$cms_component = implode("_", $cms_component);
		
		$comp_class = 'brdi_Portal_Component_'.$cms_component;
		$full_class = $comp_class;
		
		while(!class_exists($comp_class))
		{
			$comp_class = explode("_", $comp_class);
			array_pop($comp_class);
			$comp_class = implode("_", $comp_class);
		}
		try {
			$comp_builder = new $comp_class();
		}
		catch(Exception $e)
		{
			Throw new brdi_Exception(300);
		}
		// run build function
		$component_return = $comp_builder->build(array('type'=>$cms_component, 'uri'=>$full_class, 'config'=>array()));
		//set component variables
		$component_assets = $component_return[0];
		$component_html = $this->renderTokens($component_return[1], $component_return[2], $component_return[3]);
		//parse token against given content
		$template = $this->replaceToken($params['template'], $params['rawtoken'], $component_html, false);
		return $template;

	}

	/**
	 * renderAssets
	 *
	 *
	 *
	 *
	 */
	public function renderAssets($template)
	{
		$tokens = $this->getTokens($template);
		foreach($tokens[1] as $k=>$type)
		{
			$raw_token = $tokens[0][$k];
			$params = $tokens[2][$k];
			if($params[0] === "stylesheet")
			{
				if($params[1] == "global")
				{
					$template = $this->replaceToken($template, $raw_token, $this->getAllStylesheets());
				}
				else
				{
					array_shift($params);
					$asset = $this->getConfigOverride(implode("/", $params).".css");
				}
				
			}
			elseif($params[0] === "javascript")
			{
				if($params[1] == "global")
				{
					$template = $this->replaceToken($template, $raw_token, $this->getAllJavascripts());
				}
				else
				{
					array_shift($params);
					$asset = $this->getConfigOverride(implode("/", $params).".js");
				}
			}
			else
			{
				$template = $this->replaceToken($template, $raw_token, "");
			}
		}
		return $template;
	}

	/**
	 * replaceToken
	 *
	 *
	 *
	 *
	 */
	public function replaceToken($template, $token, $new, $repeat = true)
	{
		if($repeat)
		{
			return preg_replace("|".preg_quote($token)."|", $new, $template);
		}
		else {
			return preg_replace("|".preg_quote($token)."|", $new, $template, 1);
		}
	}
	
	/**
	 * getContent
	 *
	 * Parses the content
	 *
	 * @param Array $type Content type
	 * @param Array $content Content values
	 * @return String
	 */
	private function getContent($type, $content)
	{
		if(is_array($type))
		{
			$array = "\$content";
			for($i=0; $i<sizeof($type); $i++)
			{
				if(((int) $type[$i] === 0 && $type[$i] == "0") || ((int) $type[$i] > 0))
				{
					$array .= "[".$type[$i]."]";
				}
				else
				{
					$array .= "['".$type[$i]."']";
				}
			}
			// the fuck??
			try
			{
				$array = str_replace("['']", "", $array);
				eval("\$thecontent = (isset(".$array."))?".$array.":'';");
				
			}
			catch (brdi_Exception $e)
			{
				$thecontent = "";
			}
			return $thecontent;
		}
		else
		{
			return $content[$type];
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
		$template = $config['wrapper'];
		
		//$template = $this->replaceToken($template, "!{token://page}", $config['pageid']);
		$template = $this->replaceToken($template, "!{template://internal/}", $config['template']);
		// migrate from replace below - always end in trailing slash
		$template = $this->replaceToken($template, "!{template://internal}", $config['template']);
		$template = $this->renderTokens($template, array('page' => $config['pageid']), $config);
		
		
		
		$template = $this->renderAssets($template, array(), $config);
		
		// clean up any loose ends
		//mail("xnickbarone@gmail.com", "",$template);
		

		return $template;
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
		$html = "<link rel=\"stylesheet\" type=\"text/css\" href=\"/brdi/scripts/css.php?load=".urlencode(json_encode($css_files))."\" />";
		return $html;
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
		if(!$page || $page == "/") $page = "home";

		$page_batch = explode("/", $page);
		// convert to path
		$page = (substr($page, -1) == "/")?substr($page, 0, -1):$page;
		$page = ($page[0] == "/")?substr($page, 1):$page;
		$page_batch = explode("/", $page);
		if($checkcms) { if($page_batch[0] == "cms") return "cms"; }
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
