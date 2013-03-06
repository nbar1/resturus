<?php
/**
 * brdi_Portal_Page_Render
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Page_Render extends brdi_Portal_Page
{
	/**
	 * getPageTemplate
	 *
	 * Returns the template for the current page in Html format
	 * If no template is found for the current page, a 404 template is returned
	 *
	 * @return string Html formatted template of current page
	 */
	private function getPageTemplate()
	{
		try
		{
			$config = $this->getPageConfig();
			$page = $this->getPagePath();
			if(!$page) $page = "404";
			$template = (isset($config['template']))?$config['template']:$page;
			$template = $this->getConfigOverride("assets/templates/pages/".$template."/view.php");
			if(!file_exists($template)) $template = $this->getConfigOverride("assets/templates/pages/404/view.php");
			$template = file_get_contents($template);
		}
		catch(Exception $e)
		{
			$template = $this->getConfigOverride("assets/templates/404/view.php");
		}
		return $template;
	}

	/**
	 * getPageWrapper
	 *
	 * Returns the wrapper for the current page in Html format
	 *
	 * @return string Html formatted wrapper of current page
	 */
	 
	private function getPageWrapper()
	{
		try
		{
			$config = $this->getPageConfig();
			$wrapper = (isset($config['wrapper']))?$config['wrapper']:"default";
			$wrapper = $this->getConfigOverride("assets/templates/wrappers/".$wrapper.".php");
			$wrapper = file_get_contents($wrapper);
		}
		catch(Exception $e)
		{
			echo "Exception: " . $e->getMessage();
			$wrapper = false;
		}
		return $wrapper;
	}

	/**
	 * setAllPageJavascripts
	 *
	 * Sets up all the js files from the page config
	 *
	 * @return bool
	 */
	private function setAllPageJavascripts()
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
	private function setAllPageStylesheets()
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
	
	/**
	 * displayPage
	 *
	 * Gets full page html for inclusion by index.php
	 *
	 * @return String Html formatted page
	 */
	public function displayPage()
	{
		global $assets;
		$config = array(
			'wrapper' => $this->getPageWrapper(),
			'template' => $this->getPageTemplate(),
			'pageid' => str_replace("/", "_", $this->getPagePath()),
		);
		$this->setAllPageJavascripts();
		$this->setAllPageStylesheets();
		
		$template = $this->tokenize($config);
		
		return $template;
	}
}
?>
