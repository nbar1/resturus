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
	public function renderTokens($template, $content = array(), $config = array(), $skip_if = false)
	{
		$tokens = $this->getTokens($template);
		foreach($tokens[1] as $k=>$type)
		{
			$raw_token = $tokens[0][$k];
			$params = $tokens[2][$k];

			if($skip_if === true && ($type == "if" || $type == "endif")) continue;

			// check if token still exists
			if(strstr($template, $raw_token) === false) continue;

			// check for variable in token and continue if present
			if(strstr($raw_token, "[*]") !== false) continue;
			switch($type)
			{
				case "component":
					$component = implode("/", array_filter($params));
					if(false /*strtolower($component) == "pageaction"*/)
					{
						$template = $this->loadCmsComponent(array(
							'template' => $template,
							'content' => $content,
							'config' => $config,
							'rawtoken' => $raw_token,
							'tokenparams' => $params,
						));
					}
					else
					{
						$config = $this->getConfigOverride("Components/".$component."/component_config.php");
						if($config)
						{
							try
							{
								// get component
								$component_obj = $this->parseComponent($config);

								$component_return = $component_obj->buildComponent();

								//set component variables
								$component_html = $this->renderTokens($component_return[0], $component_return[1], $component_return[2]);
								//parse token against given content
								$template = $this->replaceToken($template, $raw_token, $component_html, false);
								unset($component_config);
							}
							catch (brdi_Exception $e)
							{
								throw new brdi_Exception(401, null, $this);
							}
						}
						else
						{
							$template = $this->replaceToken($template, $raw_token, "Error loading component: ".$component, false);
						}
					}
				break;

				case "html":
					$template = $this->replaceToken($template, $raw_token, $this->getContentValue($params, $content));
				break;

				case "if";
					$if_param = implode("/", $this->remove_array_empty_values($params, 0));
					$content_value = $this->getContentValue($params, $content);
					if($content_value !== false && $content_value !== null)
					{
						$this->replaceToken($template, $raw_token, "");
						$this->replaceToken($template, "!{endif://{$if_param}/}", "");
					}
					else
					{
						$delif = explode("!{if://".$if_param."/}", $template);
						$delendif = explode("!{endif://".$if_param."/}", $template);
						if(sizeof($delif) < 2 || sizeof($delendif) < 2)
						{
							$this->replaceToken($template, $raw_token, "");
							$this->replaceToken($template, "!{endif://{$if_param}/}", "");
						}
						else
						{
							$template = $delif[0].$delendif[1];
						}
					}
				break;

				case "loop":
					$loop = implode("/", array_filter($params));
					$loopdata = $this->getContentValue($params[0], $content);
					if(is_array($loopdata))
					{
						$count = sizeof($loopdata);
						preg_match("|!{loop://".$loop."/?}(.+?)!{endloop://".$loop."/?}|s", $template, $loop_content);
						$loop_content = trim($loop_content[1]);
						$loop_template = "";
						if($params[0] == "images") sort($content[$params[0]]);
						for($i=0; $i<$count; $i++)
						{
							$new_loop_content = str_replace("[*]", $i, trim($loop_content));
							$loop_template .= $this->renderTokens($new_loop_content, $content, $config);
						}
						$template = preg_replace("|!{loop://".$loop."/?}(.+?)!{endloop://".$loop."/?}|s", $loop_template, $template, 1);
					}
				break;

				case "loopvar":

					if(preg_match("|/(\d+)/x/}|", $raw_token, $key))
					{
						$template = $this->replaceToken($template, $raw_token, $key[1]);
					}
					else
					{
						$template = $this->replaceToken($template, $raw_token, $this->getContentValue($params, $content));
					}
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
					$replacement = $this->getContentValue($params, $content);
					$template = $this->replaceToken($template, $raw_token, $replacement);
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
			throw new brdi_Exception(300);
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
	 * getContentValue
	 *
	 * Parses the content
	 *
	 * @param Array $type Content type
	 * @param Array $content Content values
	 * @return String
	 */
	public function getContentValue($type, $content)
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
			try
			{
				$array = str_replace("['']", "", $array);
				eval("\$thecontent = (isset(".$array."))?".$array.":false;");

			}
			catch (brdi_Exception $e)
			{
				$thecontent = false;
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
		$template = $config['wrapper'];

		//$template = $this->replaceToken($template, "!{token://page}", $config['pageid']);
		$template = $this->replaceToken($template, "!{template://internal/}", $config['assets']['template']);
		// migrate from replace below - always end in trailing slash
		$template = $this->replaceToken($template, "!{template://internal}", $config['assets']['template']);
		$template = $this->renderTokens($template, array('page' => strtolower($config['pageid'])), $config);
		while(strstr($template, "component://") !== false)
		{
			$template = $this->renderTokens($template, array('page' => strtolower($config['pageid'])), $config, true);
		}

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
			if($js) array_push($assets['javascripts'], $js);
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
			if($css) array_push($assets['stylesheets'], $css);
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
			if(substr($js, 0, 4) == 'http')
			{
				// add remote file
				$html .= "<script type=\"text/javascript\" src=\"".$js."\"></script>";
			}
			else
			{
				$html .= "<script type=\"text/javascript\" src=\"/".$js."\"></script>";
			}
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
	 * setTemplate
	 *
	 * Get template override and set
	 *
	 * @param string template uri
	 * @return bool
	 */
	public function setTemplate($template, $set = true)
	{
		try
		{
			// If passed a uri, get the template
			if($this->isUri($template))
			{
				$uri = $this->parseUri($template);
				$template_path = strtolower($uri['type']."s/".$uri['path'].".php");
				$template = $this->getConfigOverride("assets/".$template_path);
				$template = @file_get_contents($template);

				if($template === false)
				{
					throw new brdi_Exception("Template uri pointed to invalid template");
				}
			}

			if($template)
			{
				if($set) $this->_template = $template;
				else return $template;
				return true;
			}
			else
			{
				throw new brdi_Exception("Template returned as false");
			}

		}
		catch(brdi_Exception $e)
		{
			$e->logError();
			return false;
		}
	}

	/**
	 * getTemplate
	 *
	 * @return string template html
	 */
	public function getTemplate($template = false, $set = true)
	{
		if($template)
		{
			if($set)
			{
				$this->setTemplate($template);
			}
			else
			{
				$template_noset = $this->setTemplate($template, false);
			}
		}
		try
		{
			if(!$set)
			{
				return $template_noset;
			}
			elseif(isset($this->_template))
			{
				return $this->_template;
			}
			else
			{
				throw new brdi_Exception("Tried to get template before setting template");
			}
		}
		catch(brdi_Exception $e)
		{
			$e->logError();
			return false;
		}
	}

	/**
	 * setContent
	 *
	 * Set the content array
	 *
	 * @param array $content
	 * @return bool
	 */
	public function setContent($content, $merge = true)
	{
		try
		{
			if(is_array($content))
			{
				if(is_array($this->_content) && $merge) $content = array_merge($this->_content, $content);
				$this->_content = $content;
				return true;
			}
			else
			{
				var_dump($content);
				trigger_error("ERROR");
				throw new brdi_Exception("Content set as type other than array");
			}
		}
		catch(brdi_Exception $e)
		{
			$e->logError();
			return false;
		}
	}

	/**
	 * getContent
	 *
	 * @return array|bool Content array
	 */
	public function getContent()
	{
		try
		{
			if(isset($this->_content))
			{
				return $this->_content;
			}
			else
			{
				throw new brdi_Exception("Tried to get content before setting content");
			}
		}
		catch(brdi_Exception $e)
		{
			$e->logError();
			return false;
		}
	}

	/**
	 * setParams
	 *
	 * Set the params array
	 *
	 * @param array $params
	 * @return bool
	 */
	public function setParams($params = array())
	{
		$this->_params = $params;
		return true;
	}

	/**
	 * getParams
	 *
	 * @return array|bool Params array
	 */
	public function getParams()
	{
		try
		{
			if(isset($this->_params))
			{
				return $this->_params;
			}
			else
			{
				throw new brdi_Exception("Tried to get params before setting params");
			}
		}
		catch(brdi_Exception $e)
		{
			$e->logError();
			return false;
		}
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

	// case insensitive file_exists
	function ci_file_exists($filename) {
		if (file_exists($filename))
		{
			return $filename;
		}
		$dir = dirname($filename);
		$files = glob($dir . '/*');
		$lcaseFilename = strtolower($filename);
		foreach($files as $file)
		{
			if (strtolower($file) == $lcaseFilename)
			{
			  return $file;
			}
		}
		return false;
	}
}
?>
