<?php
/**
 * brdi_Portal_Component_Footer
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Footer extends brdi_Portal_Component
{
	private $_brdi_Portal_Component_Footer = array(
		'subfooter_html' => '',
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/footer/footer.css',
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
		$config = array_merge($this->_brdi_Portal_Component_Footer, $config['config'], array('type' => $config['type']));

		// set component assets
		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$template = $this->getComponentTemplate($config);
		
		$content = array(
			'global' => array(
				'year' => date("Y"),
			),
			'subfooter' => $config['subfooter_html'],
			'client' => array(
				'name' => $this->getClientName(),
			),
		);

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template, $content, $config);
	}
}
?>