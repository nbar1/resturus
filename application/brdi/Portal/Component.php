<?php
/**
 * brdi_Portal_Component
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component extends brdi_Portal
{
	protected $_global_params = array(
		'assets' => array(
			'javascripts' => array(),
			'stylesheets' => array(),
			'template' => 'template://components/error/view/',
		),
		'class' => '',
		'columns' => 12,
		'component_title' => "COMPONENT",
		'offset' => 0,
		'show_component_title' => false,
		'wrapper' => true,
	);

	public function __construct($params = array())
	{
		$params = $this->mergeParams($params);
		$this->setParams($params);
		$this->setAllComponentJavascripts($params);
		$this->setAllComponentStylesheets($params);
		$this->setTemplate($params['assets']['template']);
		if($params['wrapper'] === true)
		{
			$this->setTemplate($this->buildComponentWrapper($this->getTemplate(), $this->getParams()));
		}
	}
	
	private function mergeParams($params)
	{
		$assets = array();
		// stylesheets
		if(!isset($this->_params['assets']['stylesheets']) && isset($params['assets']['stylesheets']))
		{
			$assets['stylesheets'] = array_merge($this->_global_params['assets']['stylesheets'], $params['assets']['stylesheets']);
		}
		elseif(isset($this->_params['assets']['stylesheets']) && !isset($params['assets']['stylesheets']))
		{
			$assets['stylesheets'] = array_merge($this->_global_params['assets']['stylesheets'], $this->_params['assets']['stylesheets']);
		}
		elseif(isset($this->_params['assets']['stylesheets']) && isset($params['assets']['stylesheets']))
		{
			$assets['stylesheets'] = array_merge($this->_global_params['assets']['stylesheets'], $this->_params['assets']['stylesheets'], $params['assets']['stylesheets']);
		}
		else
		{
			$assets['stylesheets'] = $this->_global_params['assets']['stylesheets'];	
		}
		// javascripts
		if(!isset($this->_params['assets']['javascripts']) && isset($params['assets']['javascripts']))
		{
			$assets['javascripts'] = array_merge($this->_global_params['assets']['javascripts'], $params['assets']['javascripts']);
		}
		elseif(isset($this->_params['assets']['javascripts']) && !isset($params['assets']['javascripts']))
		{
			$assets['javascripts'] = array_merge($this->_global_params['assets']['javascripts'], $this->_params['assets']['javascripts']);
		}
		elseif(isset($this->_params['assets']['javascripts']) && isset($params['assets']['javascripts']))
		{
			$assets['stylesheets'] = array_merge($this->_global_params['assets']['javascripts'], $this->_params['assets']['javascripts'], $params['assets']['javascripts']);
		}
		else
		{
			$assets['javascripts'] = $this->_global_params['assets']['javascripts'];	
		}
		// template
		if(isset($params['assets']['template']))
		{
			$assets['template'] = $params['assets']['template'];
		}
		elseif(isset($this->_params['assets']['template']))
		{
			$assets['template'] = $this->_params['assets']['template'];
		}
		else
		{
			$assets['template'] = $this->_globalparams['assets']['template'];
		}
		$params = array_merge($this->_global_params, $this->_params, $params);
		$params['assets'] = $assets;
		return $params;
	}
	
	public function buildComponent()
	{
		$params = $this->getParams();

		if(isset($params['action']) && is_callable(array($this, $action)))
		{
			$action = $params['action'];
			return $this->$action();
		}
		elseif(method_exists($this, "actionDefault"))
		{
			return $this->actionDefault();
		}
		else
		{
			return array($this->getTemplate(), $this->getContent(), $this->getParams());
		}
	}

	/**
	 * getComponentTemplate
	 *
	 * Gets the component specific template
	 *
	 * @param Array $config Component configuration
	 * @return String Component template
	 */
	public function getComponentTemplate($config)
	{
		var_dump($_SERVER);
		try
		{
			$template = strtolower(str_replace("_", "/", $config['template']));
			//if($_GET['__e']) echo "trying to load "."assets/templates/components/".strtolower($template)."/view.php<br />";
			$template = $this->getConfigOverride("assets/templates/components/".strtolower($template)."/view.php");
			$template_path = $template;
			if($template !== FALSE)
			{
				$template = @file_get_contents($template);
			}
		}
		catch(Exception $e)
		{
			$template = $this->getConfigOverride("assets/templates/component/error/view.php");
		}
		// parse component class
		if(isset($config['class']))
		{
			$template = $this->replaceToken($template, "!{token://component_class}", $config['class']);
		}
		// parse component template name
		$template = $this->replaceToken($template, "!{token://template_name}", $template_path);

		// parse any components in this component
		//$template = $this->renderTokens($template);

		return $template;
	}

	/**
	 * setAllComponentJavascripts
	 *
	 * Sets up all the js files from the component config
	 *
	 * @param Array $config Component configuration
	 * @return bool
	 */
	public function setAllComponentJavascripts($config)
	{
		if(isset($config['assets']['javascripts']))
		{
			foreach($config['assets']['javascripts'] as $js)
			{
				$this->addJavascript($js);
			}
		}
		return true;
	}

	/**
	 * setAllComponentStylesheets
	 *
	 * Sets up all the css files from the component config
	 *
	 * @param Array $config Component configuration
	 * @return bool
	 */
	public function setAllComponentStylesheets($config)
	{
		if(isset($config['assets']['stylesheets']))
		{
			foreach($config['assets']['stylesheets'] as $css)
			{
				$this->addStylesheet($css);
			}
		}
		return true;
	}
	
	/**
	 * buildComponentWrapper
	 *
	 * Wraps the component, defining a column or offset width
	 *
	 * @param String $template Component template
	 * @param Array $config Component configuration
	 * @return String Component wrapped
	 */
	public function buildComponentWrapper($template, $config)
	{
		global $columns_max;
		global $columns_at;
		$wrapper_class = "";
		$wrapper = "";

		// reset column position if needed
		if($columns_at > 12) $columns_at = 1;

		// get columns and offset
		$columns = (isset($config['columns']))?$config['columns']:12;
		$offset = (isset($config['offset']))?$config['offset']:0;

		// keep columns and offset within range
		if($columns < 1) $columns = 1;
		if($columns > $columns_max) $columns = $columns_max;
		$wrapper_class .= " span".$columns;
		if($offset < 0 || $offset > ($columns_max-1)) $offset = 0;
		if($offset > 0) $wrapper_class .= " offset".$offset;

		$wrapper_class = trim($wrapper_class);

		//$wrapper .= "<!--CHECK end row because not enough space: {$columns_at} + {$columns} + {$offset} -1 = " . ($columns_at + $columns + $offset - 1) ." gt ". $columns_max." -->";

		if(($columns_at + $columns + $offset - 1) > $columns_max)
		{
			$wrapper .= "</div>";
			$columns_at = 1;
		}
		if($columns_at == 1) $wrapper .= "<div class=\"row-fluid\">";
		if(!isset($config['class'])) $config['class'] = "";
		$wrapper .= "<div class=\"component ".trim($wrapper_class." component_".get_class($this)." ".$config['class'])."\">";
		if($config['show_component_title'] === true)
		{
			$wrapper .= "<div class=\"comp_header\">{$config['component_title']}</div>";
		}
		$wrapper .= "<div class=\"comp_body\">{$template}</div>";
		$wrapper .= "</div>";

		$columns_at += $columns + $offset;

		if($columns_at >= $columns_max)
		{
			$wrapper .= "</div>";
			$columns_at = 1;
		}

		return $wrapper;
	}
	
	/**
	 * getImagesFromCategory
	 */
	public function getImagesFromCategory($category)
	{
		global $db;
		$data = array($this->getClientId(), $category, 1);
		$dbh = $db->prepare("SELECT * FROM client_images WHERE client_id=? AND image_category=? AND image_active=?");
		$dbh->execute($data);
		$dbh->setFetchMode(PDO::FETCH_ASSOC);				
		$images = $dbh->fetchAll();
		foreach($images as $k=>$image)
		{
			$images[$k]['src'] = "cmsimages/".$images[$k]['image_filename'];
			$images[$k]['src_thumb'] = "cmsimages/thumbs/".$images[$k]['image_filename'];
			$images[$k]['description'] = $images[$k]['image_description'];
			$images[$k]['title'] = $images[$k]['image_title'];
			unset($images[$k]['image_title'], $images[$k]['image_filename'], $images[$k]['image_description']);
		}
		return $images;
	}
	
	public function __get($x)
	{
		return "hello";
	}
}