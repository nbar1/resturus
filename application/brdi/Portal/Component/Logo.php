<?php
/**
 * brdi_Portal_Component_Logo
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Logo extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_Logo = array(
		'src' => 'assets/images/components/logo/logo.png',
		'href' => '/',
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/logo/logo.css',
			),
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
	public function build($config)
	{
		$config = array_merge($this->_brdi_Portal_Component_Logo, $config['config'], array('type' => $config['type']));

		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$template = $this->getComponentTemplate($config);

		$template = $this->parseToken($template, "token://logo/src", $this->getConfigOverride($config['src']));
		$template = $this->parseToken($template, "token://logo/href", $config['href']);

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template);
	}
}
?>