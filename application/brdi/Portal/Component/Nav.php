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

	private $_brdi_Portal_Component_Nav = array(
		'nav' => array(
			'menu',
			'specials',
			'locations',
			'orderonline',
		),
		'show_title' => true,
		'center' => false,
		'links_only' => false,
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/nav/nav.css',
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
		$config = array_merge($this->_brdi_Portal_Component_Nav, $config['config'], array('type' => $config['type']));
		
		if($config['center'] === true)
		{
			array_push($config['assets']['stylesheets'], 'assets/stylesheets/components/nav/nav_centered.css');
			array_filter($config['assets']['stylesheets']);
		}
		if($config['links_only'] === true)
		{
			array_push($config['assets']['stylesheets'], 'assets/stylesheets/components/nav/nav_links.css');
			array_filter($config['assets']['stylesheets']);
		}

		$this->nav = $config['nav'];

		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$template = $this->getComponentTemplate($config);
		
		$content = array(
			'nav' => $this->getNav(),
			'page' => array(
				'title' => $this->getClientName(),
			),
			'client' => array(
				'name' => $this->getClientName(),
			),
		);

		if(!$config['show_title'] === true)
		{
			$content['client']['name'] = "";
		}

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template, $content, $config);
	}

	private function getNav()
	{
		$navitems = array();
		foreach($this->nav as $navitem)
		{
			@include($this->getConfigOverride("/page/".$navitem.".php"));
			if($page_config)
			{
				if($this->isThisPage($navitem)) 
				{
					$this->pageTitle = $page_config['title'];
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
				if($navitem == $this->nav[0])
				{
					$nav_builder['first'] = "first";
					$nav_builder['pre'] = "";
				}
				array_push($navitems, $nav_builder);
			}
		}
		return $navitems;
	}
	/**
	 * getPageNav
	 *
	 * Returns html formatted nav
	 *
	 * @return String Html formatted nav
	 */
	private function getNavBar($mobile = false)
	{
		$nav_raw = "";
		foreach($this->nav as $navitem)
		{
			include($this->getConfigOverride("/page/".$navitem.".php"));
			if($page_config)
			{
				if($this->isThisPage($navitem)) 
				{
					$this->pageTitle = $page_config['title'];
				}
				if($navitem == $this->nav[0])
				{
					$nav_builder = "<li class='divider-vertical first'></li>";
				}
				else
				{
					$nav_builder = "<li class='divider-vertical'></li>";
				}
				$li_class = "";
				if($this->isThisPage($navitem)) $li_class = " class='active'";
				$nav_builder .= "<li{$li_class}>";
				$nav_builder .= "<a href='{$page_config['href']}'";
				if(isset($page_config['class'])) $nav_builder .= " class='{$page_config['class']}'";
				$nav_builder .= ">{$page_config['title']}</a></li>";
				if($navitem === end($this->nav)) $nav_builder .= "<li class='divider-vertical'></li>";
				$nav_raw .= $nav_builder;
				unset($li_class, $page_config);
			}
		}
		// build nav for phone
		return $nav_raw;
	}
	
	/**
	 * getNavLinks
	 *
	 * Returns html formatted nav links
	 *
	 * @return String Html formatted nav links
	 */
	public function getNavLinks()
	{
		$nav_raw = "";
		foreach($this->nav as $navitem)
		{
			include($this->getConfigOverride("/page/".$navitem.".php"));
			if($page_config)
			{
				$nav_builder = "";
				if($navitem !== $this->nav[0])
				{
					$nav_builder .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
				}
				$nav_builder .= "<a href='{$page_config['href']}'>{$page_config['title']}</a>";

				$nav_raw .= $nav_builder;
				unset($page_config);
			}
		}
		return $nav_raw;
	}
}
?>