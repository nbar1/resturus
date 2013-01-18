<?php
require_once('db.php');

$client = array();
$assets = array(
	'javascripts' => array(),
	'stylesheets' => array(),
);
$columns_at = 1;
$columns_max = 12;


define('INCLUDES','brdi/includes/');
define('CONFIG','config/');
require_once(INCLUDES."autoloader.class.php");

define('STATUS','development');

$template = new brdi_Portal_Page_Render();

echo $template->displayPage();
?>