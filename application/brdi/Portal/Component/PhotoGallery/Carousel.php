<?php
/**
 * brdi_Portal_Component_PhotoGallery_ADSlider
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_PhotoGallery_Carousel extends brdi_Portal_Component
{
	public $_params = array(
		'images' => array(),
		'image_category' => false,
		'show_arrows' => true,
		'show_indicators' => true,
		'transition' => 'fade', // fade[broken], slide
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/photogallery/carousel/carousel.css',
				'assets/stylesheets/components/photogallery/carousel/carousel_override.css',
			),
			'template' => 'template://components/photogallery/carousel/view/',
			'wrapper' => false,
		),
		'class' => '',
		'columns' => 12,
	);
	/**
	 * actionDefault
	 *
	 * Builds component and returns data for Portal to render it
	 *
	 * @return Array Assets and template for component
	 */
	public function actionDefault()
	{
		$params = $this->getParams();

		if($params['image_category'] !== false)
		{
			$params['images'] = $this->getImagesFromCategory($params['image_category']);
		}

		$x=0;
		foreach($params['images'] as $image)
		{
			$content['images'][$x]['title'] = $image['title'];
			$content['images'][$x]['src'] = $this->getConfigOverride($image['src']);
			$content['images'][$x]['thumb'] = $this->getConfigOverride($image['src_thumb']);
			$content['images'][$x]['active'] = isset($image['active'])?true:false;
			if(isset($image['description'])) $content['images'][$x]['description'] = $image['description'];
			$x++;
		}
		$content['images'][0]['active'] = true;
		$content['show_arrows'] = $params['show_arrows'];
		$content['show_indicators'] = $params['show_indicators'];
		$content['transition'] = $params['transition'];

		if(isset($content) && is_array($content)) $this->setContent($content);

		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}