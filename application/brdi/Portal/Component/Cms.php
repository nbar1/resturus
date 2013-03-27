<?php
/**
 * brdi_Cms
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Cms extends brdi_Portal_Component
{
	private $_template;
	private $_content;
	
	

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
		$config = array_merge($this->default_config, $config);

		$attr = $this->getUriAttr($config['uri']);

		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$this->setTemplate($this->getComponentTemplate($config));
		
		if(!$this->runAction($attr))
		{
			$this->actionDefault();
		}

		return array(array($this->javascripts, $this->stylesheets), $this->getTemplate(), $this->getContent(), $config);
	}

	/**
	 * getUriAttributes
	 *
	 *
	 *
	 *
	 */
	public function getUriAttr($uri)
	{
		$vals = str_ireplace(get_class($this)."_", "", $uri);
		$vals = explode("_", $vals);
		$attr['method'] = array_shift($vals);
		$this->setParams($vals);

		return $attr;
	}
	
	
	public function runAction($attr)
	{
		if(method_exists($this, "action".$attr['method']))
		{
			call_user_func(array($this,'action'.$attr['method']), $this->getParams());
			return true;
		}
		else
		{
			return false;
		}
		
	}
	
	public function setTemplate($template)
	{
		$this->_template = $template;
		return true;
	}
	
	public function getTemplate()
	{
		return $this->_template;
	}
	
	public function setContent($content)
	{
		$this->_content = $content;
		return true;
	}
	
	public function getContent()
	{
		return $this->_content;
	}
	
	public function setparams($params = array())
	{
		$this->_params = $params;
		return true;
	}
	
	public function getParams()
	{
		return $this->_params;
	}
}
?>
