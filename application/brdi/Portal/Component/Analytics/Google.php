<?php
/**
 * brdi_Portal_Component_Analytics_Google
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Analytics_Google extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_Analytics_Google = array(
		'analytics' => array(
			'google' => array(
				'accountid' => 'UA-39473337-1',
				'domain' => 'resturus.com',
			),
		),
		'columns' => 0,
		'offset' => 0,
	);

	/**
	 * build
	 *
	 * Builds component and returns data for Portal to render it
	 *
	 * @param Array $config Component configuration
	 * @return Array Assets and template for component
	 */
	public function build($params)
	{
		$config = array_merge($this->_brdi_Portal_Component_Analytics_Google, $params['config'], array('type' => $params['type']));
		$template = $this->getComponentTemplate($config);
		
		$content = $config;
		//$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template, $content, $config);
	}
}
?>