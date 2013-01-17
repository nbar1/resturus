<?php
require_once('db.php');

$client = array();
$assets = array(
	'javascripts' => array(),
	'stylesheets' => array(),
);


define('INCLUDES','brdi/includes/');
define('CONFIG','config/');
require_once(INCLUDES."autoloader.class.php");

define('STATUS','development');

$template = new brdi_Portal_Page_Render();

echo $template->displayPage();
?>