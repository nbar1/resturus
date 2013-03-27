<?php
/**
 * brdi_Portal_Component_PhotoGallery_ADSlider
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_PhotoGallery_ADSlider extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_PhotoGallery_ADSlider = array(
		'images' => array(),
		'image_category' => false,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/photogallery/adslider/adslider.css',
				'assets/stylesheets/components/photogallery/adslider/slim.css',
			),
			'javascripts' => array(
				'assets/javascripts/components/photogallery/adslider/gallery.js',
				'assets/javascripts/components/photogallery/adslider/jquery.easing.1.3.js',
				'assets/javascripts/components/photogallery/adslider/jquery.elastislide.js',
				'assets/javascripts/components/photogallery/adslider/jquery.tmpl.min.js',
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
		$config = array_merge($this->_brdi_Portal_Component_PhotoGallery_ADSlider, $config['config'], array('type' => $config['type']));
		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$template = $this->getComponentTemplate($config);
		
		if($config['image_category'] !== false)
		{
			$config['images'] = $this->getImagesFromCategory($config['image_category']);
		}

		$x=0;
		foreach($config['images'] as $image)
		{
			$content['images'][$x]['title'] = $image['title'];
			$content['images'][$x]['src'] = $this->getConfigOverride($image['src']);
			$content['images'][$x]['thumb'] = $this->getConfigOverride($image['src_thumb']);
			if(isset($image['description'])) $content['images'][$x]['description'] = $image['description'];
			$x++;
		}

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template, $content, $config);
	}
}