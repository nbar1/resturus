<?php

class brdi_Portal_Component extends brdi_Portal
{
	public function getComponentTemplate($config)
	{
		try
		{
			if($config[1]['template'])
			{
				$template = $config[1]['template'];
			}
			$page = $this->getPagePath();
			if(!$page) $page = "404";
			$template = (!$config[1]['template'])?$config[0]:$config[1]['template'];
			$template = $this->getConfigOverride("assets/templates/components/".strtolower($template)."/view.php");
			$template = file_get_contents($template);
		}
		catch(Exception $e)
		{
			$template = $this->getConfigOverride("assets/templates/error/view.php");
		}
		$template = $this->parseToken($template, "token://component_class", $config[1]['config']['class']);
		return $template;
	}
	
	/**
	 * setAllComponentJavascripts
	 *
	 * Sets up all the js files from the component config
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
	 */
	public function setAllComponentStylesheets($config)
	{
		foreach($config['assets']['stylesheets'] as $css)
		{
			$this->addStylesheet($css);
		}
		return true;
	}
	
	
	public function buildComponents($config)
	{
		// example config
		/*
		array(
			array(
				'type' => 'Location',
				'config' => array(
					'location_id' => 123,
					'class' => 'hp_component',
				),
				'columns' => 10,
				'offset' => 1,
			),
		),
		*/
		
		
		
		if(!is_array($config)) return "Error rendering components";
		
		$columns = 12;
		$col_at = 1;
		
		foreach($config as $comp)
		{
			$comp_class = 'brdi_Page_Component_'.$comp['type'];
			$comp_builder = new $comp_class($comp);
			if($comp_builder->isUsable())
			{
				if($comp['columns'] < 1) $comp['columns'] = 1;
				if($comp['columns'] > 12) $comp['columns'] = 12;
				if(($comp['offset'] < 0) || ($comp['offset'] > 11)) $comp['offset'] = 0;
				$comp['config']['class'] = $comp['config']['class'] . " span" . $comp['columns'];
				if($comp['offset'] > 0) $comp['config']['class'] .= " offset" . $comp['offset'];
	
				if(($columns - ($comp['columns'] + $comp['offeset']) < ($col_at-1)))
				{
					echo "</div><!--end row because " . ($columns - ($comp['columns'] + $comp['offeset'])) . " < " . $col_at . "-->";
					$col_at = 1;
				}
				if($col_at==1) echo "<div class='row-fluid'>";
				call_user_func(array($comp_builder, 'actionBuild'), $comp);
				if($comp['columns'] >= 12) echo "</div><!-- end row because component columnd >= 12 -->";
				$col_at += $comp['columns'];
			}
		}
		echo "</div>";
		
	}
}