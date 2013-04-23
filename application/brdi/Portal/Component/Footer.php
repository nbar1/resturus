<?php
/**
 * brdi_Portal_Component_Footer
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Footer extends brdi_Portal_Component
{
	protected $_params = array(
		'subfooter_html' => '',
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/footer/footer.css',
			),
			'template' => 'template://components/footer/view/',
		),
		'subfooter_html' => '',
	);

	/**
	 * actionDefault
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
			'global' => array(
				'year' => date("Y"),
			),
			'subfooter' => $params['subfooter_html'],
			'client' => array(
				'name' => $this->getClientName(),
			),
		);

		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>