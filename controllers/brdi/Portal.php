<?php
class brdi_Portal extends brdi
{

	/**
	 * getFileOverrides
	 */
	public function getConfigOverride($path)
	{
		if(!$path) return false;
		if(file_exists(CONFIG.$this->getClientToken()."/".$path))
		{
			return CONFIG.$this->getClientToken()."/".$path;
		}
		elseif(file_exists(CONFIG."default/".$path))
		{
			return CONFIG."default/".$path;
		}
		else
		{
			return false;
		}
	}

	public function tokenize($config)
	{
		global $assets;
		/**
		 * config Array()
		 *
		 * wrapper
		 * template
		 * components Array()
		 ** Array()
		 *** 
		 *
		 *
		 */
		 $content = $config['wrapper'];
		 $content = $this->parseToken($content, "template://internal", $config['template']);
		 $content = $this->parseAllComponents($content);
		 $content = $this->parseToken($content, "asset://stylesheets", $this->getAllStylesheets());
		 $content = $this->parseToken($content, "asset://javascripts", $this->getAllJavascripts());
		 $content = $this->parseToken($content, "token://page", $config['pageid']);
		 
		 return $content;
		 
	}

	public function parseToken($data, $token, $replace)
	{
		return preg_replace("|\!\{".preg_quote($token)."\}|", $replace, $data);
	}

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

				$component_return = $comp_builder->build(array($component, $component_config));

				$component_html = $component_return[1];
				$component_assets = $component_return[0];
				$content = $this->parseToken($content, "component://".$component, $component_html);
			}
		}
		return $content;
	}
	
	
	
	public function addJavascript($javascript)
	{
		global $assets;
		if(substr($javascript, 0, 4) == 'http')
		{
			array_push($assets['javascripts'], $javascript);
		}
		else {
			$js = $this->getConfigOverride($javascript);
			if($js) array_push($assets['javascripts'], $this->getConfigOverride($javascript));
		}
		return true;
	}
	
	public function addStylesheet($stylesheet)
	{
		global $assets;
		if(substr($stylesheet, 0, 4) == 'http')
		{
			array_push($assets['stylesheets'], $stylesheet);
		}
		else {
			$css = $this->getConfigOverride($stylesheet);
			if($css) array_push($assets['stylesheets'], $this->getConfigOverride($stylesheet));
		}
		return true;
	}
	
	private function getAllJavascripts()
	{
		global $assets;
		$html = "";
		foreach($assets['javascripts'] as $js)
		{
			$html .= "<script type=\"text/javascript\" src=\"/".$js."\"></script>";
		}
		return $html;
	}
	
	private function getAllStylesheets()
	{
		global $assets;
		$html = "";
		foreach($assets['stylesheets'] as $css)
		{
			$html .= "<link rel=\"stylesheet\" href=\"/".$css."\" />";
		}
		return $html;
	}
	
	/**
	 * getPagePath
	 */
	public function getPagePath()
	{
		$page = strtolower(str_replace("/index.php","", $_SERVER['REQUEST_URI']));

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
		if($this->getPageHref() == strtolower(str_replace("/","",$config_page))) return true;
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