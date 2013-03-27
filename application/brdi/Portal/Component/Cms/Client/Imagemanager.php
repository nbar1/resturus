<?php
/**
 * brdi_Portal_Component_Cms_Client_Imagemanager
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Cms_Client_ImageManager extends brdi_Portal_Component_Cms_Client
{

	protected $default_config = array(
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/cms/client/imagemanager.css',
			),
		),
		'template' => 'cms/imagemanager',
	);
	
	public function actionDefault()
	{
		$this->setContent(array(
			'yolo' => 'who',
		));
	}
	
	public function actionShowImages()
	{
		$params = $this->getParams();

		$this->setContent(array(
			'yolo' => $params[0],
		));
	}
}
?>