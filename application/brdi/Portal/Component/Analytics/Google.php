<?php
/**
 * brdi_Portal_Component_Analytics_Google
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Analytics_Google extends brdi_Portal_Component
{
	protected $_params = array(
		'analytics' => array(
			'google' => array(
				'accountid' => 'UA-39473337-1',
				'domain' => 'resturus.com',
			),
		),
		'assets' => array(
			'template' => 'template://components/analytics/google/view/',
		),
		'columns' => 0,
		'offset' => 0,
		'wrapper' => false,
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
		
		$this->setContent($this->getParams());

		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>