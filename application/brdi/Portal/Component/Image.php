<?php
/**
 * brdi_Portal_Component_Image
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Image extends brdi_Portal_Component
{
	protected $_params = array(
		'src' => array(
			'src' => false,
			'src_class' => "",
		),
		'is_url' => false,
		'url' => array(
			'href' => "",
			'target' => "",
			'class' => "",
		),
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/image/image.css',
			),
			'template' => 'template://components/image/view/',
		),
		'wrapper' => 'template://wrappers/component_bare/',
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
		
		if($params['is_url'] !== false && $params['is_url'] !== null)
		{
			$params['is_url'] = array(
				'start' => true,
				'end' => true,
			);
		}
		else
		{
			$params['is_url'] = array(
				'start' => false,
				'end' => false,
			);
		}

		$this->setContent($params);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>