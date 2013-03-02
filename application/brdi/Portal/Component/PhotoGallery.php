<?php
/**
 * brdi_Portal_Component_PhotoGallery
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_PhotoGallery extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_PhotoGallery = array(
		'images' => array(),
		'style' => 'large',
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/slider/nivo-slider.css',
				'assets/stylesheets/components/slider/slider.css',
			),
			'javascripts' => array(
				'assets/javascripts/components/slider/jquery.nivo.slider.pack.js',
			),
		),
		'class' => '',
		'columns' => 12,
	);
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
		$config = array_merge($this->_brdi_Portal_Component_PhotoGallery, $config['config'], array('type' => $config['type']));
		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);
		
		if($config['style'] === "large")
		{
			$config['template'] = "photogallery_large";
		}
		elseif($config['style'] === "modal") {
			$config['template'] = "photogallery_modal";
		}

		$template = $this->getComponentTemplate($config);

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template);
	}
	
	private function buildImages($config)
	{
		
	}
}