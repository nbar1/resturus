<?php
//ob_start ("ob_gzhandler");
require_once('framework_helper.php');

$client = array();
$assets = array(
	'javascripts' => array(),
	'stylesheets' => array(),
);
$columns_at = 1;
$columns_max = 12;

$template = new brdi_Portal_Page_Render();

echo $template->displayPage();
?>