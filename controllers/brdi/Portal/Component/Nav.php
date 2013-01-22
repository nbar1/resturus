<?php
/**
 * brdi_Portal_Component_Nav
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Nav extends brdi_Portal_Component
{
	public $nav;
	public $config;
	public $type;

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
		$this->config = $config[1]['config'];
		$this->nav = $config[1]['config']['nav'];
		$this->type = $config[0];

		$this->setAllComponentJavascripts($this->config);
		$this->setAllComponentStylesheets($this->config);

		$template = $this->getComponentTemplate($config);

		$template = $this->parseToken($template, "token://clientName", $this->getClientName());
		$template = $this->parseToken($template, "token://pageNav", $this->getPageNav());
		$template = $this->parseToken($template, "token://mobileNav", $this->getPageNavMobile());

		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template);
	}

	/**
	 * getPageNav
	 *
	 * Returns html formatted nav for Page/Tablet display
	 *
	 * @return String Html formatted nav
	 */
	private function getPageNav()
	{
		$nav_raw = "";
		foreach($this->nav as $navitem)
		{
			include($this->getConfigOverride("/page/".$navitem.".php"));
			if($page_config)
			{
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
				if(isset($page_config['class'])) $nav_builder .= " class='z{$page_config['class']}'";
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
	 * getPageNavMobile
	 *
	 * Returns html formatted nav for Mobile display
	 *
	 * @return String Html formatted nav
	 */
	private function getPageNavMobile()
	{
		$nav_raw = "";
		foreach($this->nav as $navitem)
		{
			include($this->getConfigOverride("/page/".$navitem.".php"));
			if($page_config)
			{
				if($navitem == $this->nav[0])
				{
					$nav_builder = "<li class='divider first'></li>";
				}
				else
				{
					$nav_builder = "<li class='divider'></li>";
				}
				$li_class = "";
				if($this->isThisPage($navitem)) $li_class = " class='active'";
				$nav_builder .= "<li{$li_class}>";
				$nav_builder .= "<a href='{$page_config['href']}'";
				if(isset($page_config['class'])) $nav_builder .= " class='{$page_config['class']}'";
				$nav_builder .= ">{$page_config['title']}</a></li>";
				if($navitem === end($this->nav)) $nav_builder .= "<li class='divider-vertical'></li>";
				$nav_raw .= $nav_builder;
				unset($li_class);
			}
		}
		// build nav for phone
		return $nav_raw;
	}
}
?>