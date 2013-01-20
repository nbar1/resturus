<?php
/**
 * brdi_Portal_Component
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component extends brdi_Portal
{
	/**
	 * getComponentTemplate
	 *
	 * Gets the component specific template
	 *
	 * @param Array $config Component configuration
	 * @return String Component template
	 */
	public function getComponentTemplate($config)
	{
		try
		{
			if($config[1]['template'])
			{
				$template = $config[1]['template'];
			}
			$template = (!$config[1]['template'])?$config[0]:$config[1]['template'];
			$template = $this->getConfigOverride("assets/templates/components/".strtolower($template)."/view.php");
			$template_path = $template;
			$template = file_get_contents($template);
		}
		catch(Exception $e)
		{
			$template = $this->getConfigOverride("assets/templates/component/error/view.php");
		}
		// parse component class
		$template = $this->parseToken($template, "token://component_class", $config[1]['config']['class']);
		// parse component template name
		$template = $this->parseToken($template, "token://template_name", $template_path);
		return $template;
	}

	/**
	 * setAllComponentJavascripts
	 *
	 * Sets up all the js files from the component config
	 *
	 * @param Array $config Component configuration
	 * @return bool
	 */
	public function setAllComponentJavascripts($config)
	{
		foreach($config['assets']['javascripts'] as $js)
		{
			$this->addJavascript($js);
		}
		return true;
	}

	/**
	 * setAllComponentStylesheets
	 *
	 * Sets up all the css files from the component config
	 *
	 * @param Array $config Component configuration
	 * @return bool
	 */
	public function setAllComponentStylesheets($config)
	{
		foreach($config['assets']['stylesheets'] as $css)
		{
			$this->addStylesheet($css);
		}
		return true;
	}
	
	/**
	 * buildComponentWrapper
	 *
	 * Wraps the component, defining a column or offset width
	 *
	 * @param String $template Component template
	 * @param Array $config Component configuration
	 * @return String Component wrapped
	 */
	public function buildComponentWrapper($template, $config)
	{
		global $columns_max;
		global $columns_at;
		$wrapper_class = "";
		$wrapper = "";

		// reset column position if needed
		if($columns_at > 12) $columns_at = 1;

		// get columns and offset
		$columns = (isset($config[1]['config']['columns']))?$config[1]['config']['columns']:12;
		$offset = (isset($config[1]['config']['offset']))?$config[1]['config']['offset']:0;

		// keep columns and offset within range
		if($columns < 1) $columns = 1;
		if($columns > $columns_max) $columns = $columns_max;
		$wrapper_class .= " span".$columns;
		if($offset < 0 || $offset > ($columns_max-1)) $offset = 0;
		if($offset > 0) $wrapper_class .= " offset".$offset;

		$wrapper_class = trim($wrapper_class);

		//$wrapper .= "<!--CHECK end row because not enough space: {$columns_at} + {$columns} + {$offset} -1 = " . ($columns_at + $columns + $offset - 1) ." gt ". $columns_max." -->";

		if(($columns_at + $columns + $offset - 1) > $columns_max)
		{
			$wrapper .= "</div><!--3-->";
			$columns_at = 1;
		}
		if($columns_at == 1) $wrapper .= "<div class=\"row-fluid\">";

		$wrapper .= "<div class=\"".$wrapper_class." component_".$config[0]."\">";
		$wrapper .= $template;
		$wrapper .= "</div><!--2-->";

		$columns_at += $columns + $offset;

		if($columns_at >= $columns_max)
		{
			$wrapper .= "</div><!--1-->";
			$columns_at = 1;
		}

		return $wrapper;
	}
}