<?php
/**
 * brdi_Portal_Component_Logo
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Logo extends brdi_Portal_Component
{
	protected $_params = array(
		'src' => 'assets/images/components/logo/logo.png',
		'href' => '/',
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/logo/logo.css',
			),
			'template' => 'template://components/logo/view/',
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
		$params = $this->getParams();

		$content = array(
			'logo' => array(
				'src' => "/".$this->getConfigOverride($params['src']),
				'href' => $params['href'],
			),
		);

		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>