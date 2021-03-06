<?php
/**
 * brdi_Portal_Component_Tabbed
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Tabbed extends brdi_Portal_Component
{
	protected $_params = array(
		'tabs' => array(
			array(
				'title' => '',
				'content' => '',
				'active' => false,
			),
		),
		'position' => 'top', // top
		'assets' => array(
			'javascripts' => array(),
			'stylesheets' => array(
				'assets/stylesheets/components/tabbed/tabbed.css',
			),
			'template' => 'template://components/tabbed/default/',
		),
		'wrapper' => "template://wrappers/component_bare/",
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

		$this->setContent($params);

		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>