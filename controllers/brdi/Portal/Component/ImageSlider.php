<?php
/**
 * brdi_Portal_Component_ImageSlider
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_ImageSlider extends brdi_Portal_Component
{
	/**
	 * build
	 *
	 * Builds component and returns data for Portal to render it
	 *
	 * @param Array $config Component configuration
	 * @return Array Assets and template for component
	 */
	public function build($config)
	{
		$this->config = $config[1]['config'];
		$this->type = $config[0];

		$this->setAllComponentJavascripts($this->config);
		$this->setAllComponentStylesheets($this->config);

		$template = $this->getComponentTemplate($config);

		$x=1;
		foreach($this->config['images'] as $image)
		{
			$template = $this->parseToken($template, "image://".$x, $this->getConfigOverride($image));
			$x++;
		}

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template);
	}
}