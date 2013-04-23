<?php
/**
 * brdi_Portal_Component_RawHtml
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_RawHtml extends brdi_Portal_Component
{
	protected $_params = array(
		'html' => array(),
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

		$content = $params['html'];

		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>