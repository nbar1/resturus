<?php
/**
 * brdi_Portal_Component_Cms_Navigation
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Cms_Navigation extends brdi_Portal_Component_Cms
{

	protected $_params = array(
		'nav' => array(
			'account' => array(
				'title' => 'Account',
				'nav' => array(
					array(
						'title' => 'Dashboard',
						'href' => '/cms/',
					),
					array(
						'title' => 'Subscription',
						'href' => '/cms/account/subscription/',
					),
					array(
						'title' => 'Account Settings',
						'href' => '/cms/account/settings/',
					),
				),
			),
			'client' => array(
				'title' => 'Client',
				'nav' => array(
					array(
						'title' => 'Image Manager',
						'href' => '/cms/client/imagemanager/',
					),
					array(
						'title' => 'Social Media',
						'href' => '/cms/client/socialmedia/',
					),
					array(
						'title' => 'Locations',
						'href' => '/cms/client/locations/',
					),
				),
			),
			'requests' => array(
				'title' => 'Requests',
				'nav' => array(
					array(
						'title' => 'Change Request',
						'href' => '/cms/requests/changerequest/',
					),
				),
			),
		),
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/cms/navigation/navigation.css',
			),
			'template' => 'template://components/cms/navigation/view/',
		),
		'wrapper' => false,
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
		$params = $this->getParams();
		$content = array(
			'nav' => $params['nav'],
		);

		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}
}
?>