<?php
/**
 * brdi_Portal_Component_Menu
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Component_Menu extends brdi_Portal_Component
{
	public $menu;
	public $menuitems;
	public $currency_type;

	protected $_brdi_Portal_Component_Menu = array(
		'currency_type' => '$',
		'assets' => array(
			'stylesheets' => array(
				'assets/stylesheets/components/menu/menu_default.css',
			),
		),
		'class' => '',
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
		$config = array_merge($this->_brdi_Portal_Component_Menu, $config['config'], array('type' => $config['type']));

		// get location_id from config or default
		if(isset($config['menu_id']))
		{
			$this->menu = $this->getMenuFromDatabase($config['menu_id']);
		}
		else {
			return $this->bombComponent();
		}

		// Currency type
		if(isset($config['currency_type']))
		{
			$this->currency_type = $config['currency_type'];
		}
		else {
			$this->currency_type = "";
		}

		// set component assets
		$this->setAllComponentJavascripts($config);
		$this->setAllComponentStylesheets($config);

		$template = $this->getComponentTemplate($config);
		
		$this->getMenuFromDatabase($config['menu_id']);
		$this->getMenuItemsFromDatabase($config['menu_id']);

		$template = $this->parseToken($template, "token://menu_title", $this->menu['menu_title']);
		$template = $this->parseToken($template, "token://all_menu_items", $this->buildMenu());
		
		$template = $this->buildComponentWrapper($template, $config);

		return array(array($this->javascripts, $this->stylesheets), $template);
	}

	private function getMenuFromDatabase($menu_id)
	{
		global $db;
		if(!$menu_id) return false;

		try
		{
			$data = array($menu_id, $this->getClientId(), 1);
			$dbh = $db->prepare("SELECT * FROM menus WHERE menu_id=? AND menu_client=? AND menu_active=? LIMIT 1");
			$dbh->execute($data);
			$dbh->setFetchMode(PDO::FETCH_ASSOC);
			$this->menu = $dbh->fetch();				
			return $this->menu;
		}
		catch(Exception $e) {
			echo "Exception: " . $e->getMessage();
			return false;
		}
	}
	
	private function getMenuItemsFromDatabase($menu_id)
	{
		global $db;
		if(!$menu_id) return false;

		try
		{
			$data = array($menu_id, $this->getClientId(), 1);
			$dbh = $db->prepare("SELECT * FROM menuitems WHERE mitem_menu=? AND mitem_client=? AND mitem_active=? ORDER BY mitem_position ASC");
			$dbh->execute($data);
			$dbh->setFetchMode(PDO::FETCH_ASSOC);
			$this->menuitems = $dbh->fetchAll();				
			return $this->menuitems;
		}
		catch(Exception $e) {
			echo "Exception: " . $e->getMessage();
			return false;
		}
	}
	
	private function buildMenu()
	{
		$response = "";
		$x=0;
		foreach($this->menuitems as $mi)
		{
			if($x%2===0) 
			{
				$response .= "<div class='row-fluid'>";
				$response .= "<div class='mitem odd span6'>";
			}
			else {
				$response .= "<div class='mitem even span6'>";
			}
			
			$response .= "<div class='mitem_price'>{$this->currency_type}{$mi['mitem_price']}</div>";
			$response .= "<div class='mitem_title'>{$mi['mitem_title']}</div>";
			$response .= "<div class='mitem_description'>{$mi['mitem_description']}</div>";
			$response .= "<div class='mitem_tags'><span class='label label-info'>{$mi['mitem_tags']}</label></div>";
			$response .= "</div>";
			
			$x++;
			if($x%2===0) $response .= "</div>";
		}
		return $response;
	}
}