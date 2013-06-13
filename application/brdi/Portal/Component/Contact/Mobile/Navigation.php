<?php
class brdi_Portal_Component_Contact_Mobile_Navigation extends brdi_Portal_Component_Contact
{
	protected $_params = array(
		'zoom' => 14,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/contact/mobile/navigation.css',
				'assets/stylesheets/components/contact/mobile/navigation_override.css',
			),
			'template' => 'template://components/contact/mobile/navigation/',
		),
		'columns' => 12,
		'wrapper' => 'template://wrappers/component_bare_mobile/',
	);

	public function actionDefault()
	{
		$params = $this->getParams();

		$params['location'] = $this->getLocation();
		$params['location']['urlencoded'] = $this->getUrlEncodedAddress();
		
		$this->setContent($params);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>