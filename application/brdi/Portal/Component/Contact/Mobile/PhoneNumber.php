<?php
class brdi_Portal_Component_Contact_Mobile_PhoneNumber extends brdi_Portal_Component_Contact
{
	protected $_params = array(
		'zoom' => 14,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/contact/mobile/phonenumber.css',
				'assets/stylesheets/components/contact/mobile/phonenumber_override.css',
			),
			'template' => 'template://components/contact/mobile/phonenumber/',
		),
		'columns' => 12,
		'wrapper' => 'template://wrappers/component_bare_mobile/',
	);

	public function actionDefault()
	{
		$params = $this->getParams();

		$params['location'] = $this->getLocation();
		
		$this->setContent($params);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>