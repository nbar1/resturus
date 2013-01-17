<?php
class brdi_Portal_Component_Nav extends brdi_Portal_Component
{
	public $nav;
	public $config;
	public $type;

	public function build($config)
	{
		$this->config = $config[1];
		$this->nav = $config[1]['nav'];
		$this->type = $config[0];

		$template = $this->getComponentTemplate($config);

		$template = $this->parseToken($template, "token://clientName", $this->getClientName());
		$template = $this->parseToken($template, "token://pageNav", $this->getPageNav());
		$template = $this->parseToken($template, "token://mobileNav", $this->getPageNavMobile());

		return array(array($this->javascripts, $this->stylesheets), $template);
	}

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
				if($this->isThisPage($navitem['href'])) $li_class = " class='active'";
				$nav_builder .= "<li{$li_class}>";
				$nav_builder .= "<a href='{$page_config['href']}'";
				if($navitem['class']) $nav_builder .= " class='{$page_config['class']}'";
				$nav_builder .= ">{$page_config['title']}</a></li>";
				if($navitem === end($this->nav)) $nav_builder .= "<li class='divider-vertical'></li>";
				$nav_raw .= $nav_builder;
				unset($li_class);
			}
		}
		// build nav for phone
		return $nav_raw;
	}

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
				if($this->isThisPage($navitem['href'])) $li_class = " class='active'";
				$nav_builder .= "<li{$li_class}>";
				$nav_builder .= "<a href='{$page_config['href']}'";
				if($navitem['class']) $nav_builder .= " class='{$page_config['class']}'";
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