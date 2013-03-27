<?php
/**
 * brdi_Portal_Component_ImageSlider
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_ImageSlider extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_ImageSlider = array(
		'images' => array(),
		'image_category' => false,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/slider/nivo-slider.css',
				'assets/stylesheets/components/slider/slider.css',
			),
			'javascripts' => array(
				'assets/javascripts/components/slider/jquery.nivo.slider.pack.js',
			),
		),
		'class' => 'slider-wrapper',
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
		$config = array_merge($this->_brdi_Portal_Component_ImageSlider, $config['config'], array('type' => $config['type']));
		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$template = $this->getComponentTemplate($config);
		
		if($config['image_category'] !== false)
		{
			$config['images'] = $this->getImagesFromCategory($config['image_category']);
		}

		$x=1;
		foreach($config['images'] as $image)
		{
			if($config['image_category'] === false)
			{
				$content['images'][$x] = $this->getConfigOverride($image);
			}
			else
			{
				$content['images'][$x] = $config['images'][$x-1]['src'];
			}
			$x++;
		}

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template, $content, $config);
	}
}