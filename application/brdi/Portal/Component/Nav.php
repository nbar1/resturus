<?php
/**
 * brdi_Portal_Component_Nav
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Nav extends brdi_Portal_Component
{
	private $nav;
	private $pageTitle;

	protected $_params = array(
		'nav' => array(
			'Menu',
			'Locations',
		),
		'show_title' => true,
		'center' => false,
		'links_only' => false,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/nav/nav.css',
			),
			'template' => 'template://components/nav/default/',
		),
	);

	public function actionDefault()
	{
		$params = $this->getParams();
		
		$content = array(
			'nav' => $this->getNav(),
			'page' => array(
				'title' => $this->getClientName(),
			),
			'client' => array(
				'name' => $this->getClientName(),
			),
		);

		if(!$params['show_title'] === true)
		{
			$content['client']['name'] = "";
		}
		
		$this->setContent($content);
		return array($this->getTemplate(), $this->getContent(), $this->getParams());
	}

	private function getNav()
	{
		$params = $this->getParams();
		$navitems = array();
		foreach($params['nav'] as $navitem)
		{
			include($this->getConfigOverride("/Page/".$navitem."/page_config.php"));
			if(isset($page_config))
			{
				if($this->isThisPage($navitem)) 
				{
					$params['page_title'] = $page_config['title'];
				}
				
				$nav_builder = array(
					'title' => $page_config['title'],
					'href' => $page_config['href'],
					'class' => "",
					'active_class' => "",
					'first' => "",
					'pre' => "&nbsp;&nbsp;|&nbsp;&nbsp;",
					
				);
				if(isset($page_config['class'])) $nav_builder['class'] = $page_config['class'];
				if($this->isThisPage($navitem)) $nav_builder['active_class'] = "active";
				if($navitem == $params['nav'][0])
				{
					$nav_builder['first'] = "first";
					$nav_builder['pre'] = "";
				}
				array_push($navitems, $nav_builder);
			}
		}
		$this->setParams($params);
		return $navitems;
	}
}
?>