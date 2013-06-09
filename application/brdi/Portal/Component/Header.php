<?php
/**
 * brdi_Portal_Component_Header
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Header extends brdi_Portal_Component
{
	protected $_params = array(
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/header/logocenter.css',
			),
			'template' => 'template://components/header/logo_center/',
		),
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
		$this->setContent(array());
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>