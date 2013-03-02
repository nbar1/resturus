<?php
/**
 * brdi_Portal_Component_RawHtml
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_RawHtml extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_RawHtml = array(
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
	public function build($config)
	{
		$config = array_merge($this->_brdi_Portal_Component_RawHtml, $config['config'], array('type' => $config['type']));
		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);
		$template = $this->getComponentTemplate($config);

		$params = $config['html'];

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template, $params, $config);
	}
}
?>