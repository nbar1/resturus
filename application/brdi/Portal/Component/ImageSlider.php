<?php
/**
 * brdi_Portal_Component_ImageSlider
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_ImageSlider extends brdi_Portal_Component
{
	protected $_params = array(
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
			'template' => 'template://components/imageslider/view/',
		),
		'class' => 'slider-wrapper',
	);
	/**
	 * build
	 *
	 * Builds component and returns data for Portal to render it
	 *
	 * @param Array $config Component configuration
	 * @return Array Assets and template for component
	 */
	public function actionDefault()
	{
		$params = $this->getParams();
		
		if($params['image_category'] !== false)
		{
			$params['images'] = $this->getImagesFromCategory($params['image_category']);
		}
		
		$content = array();
		$x=1;
		foreach($params['images'] as $image)
		{
			if($params['image_category'] === false)
			{
				$content['images'][$x] = $this->getConfigOverride($image);
			}
			else
			{
				$content['images'][$x] = $params['images'][$x-1]['src'];
			}
			$x++;
		}
		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}