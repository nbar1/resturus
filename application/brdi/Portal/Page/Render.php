<?php
/**
 * brdi_Portal_Page_Render
 *
 * @author Nick Barone
 * @copyright Copyright (c) Resturus, 2013
 */
class brdi_Portal_Page_Render extends brdi_Portal_Page
{
	/**
	 * displayPage
	 *
	 * Gets full page html for inclusion by index.php
	 *
	 * @return String Html formatted page
	 */
	public function displayPage()
	{
		$this->setAllPageJavascripts();
		$this->setAllPageStylesheets();
		$template = $this->tokenize($this->getPageConfig());
		return $template;
	}
}
?>
