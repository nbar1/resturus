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

if($_GET['__e'] == true)
{
		ini_set('display_errors', '1');
}


$test = new brdi_Portal_Component_Twitter();
var_dump($test->getTimelineTweets('nbar1'));




$template = new brdi_Portal_Page_Render();

echo $template->displayPage();
?>
