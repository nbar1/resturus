<?php
class brdi_Portal_Component_ImageSlider extends brdi_Portal_Component
{
	public function build($config)
	{
		$this->config = $config[1]['config'];
		$this->type = $config[0];
		
		$this->setAllComponentJavascripts($this->config);
		$this->setAllComponentStylesheets($this->config);

		$template = $this->getComponentTemplate($config);

		$x=1;
		foreach($this->config['images'] as $image)
		{
			$template = $this->parseToken($template, "image://".$x, $this->getConfigOverride($image));
			$x++;
		}

		return array(array($this->javascripts, $this->stylesheets), $template);
	}
}