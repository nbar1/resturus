<?php
ob_start ("ob_gzhandler");

define('STATUS','development');

header('Cache-Control: max-age=1800');



require_once('db.php');

$client = array();
$assets = array(
	'javascripts' => array(),
	'stylesheets' => array(),
);
$columns_at = 1;
$columns_max = 12;


define('INCLUDES','application/includes/');
define('CONFIG','config/');
require_once(INCLUDES."autoloader.class.php");

if($_GET['__e'] == true)
{
		ini_set('display_errors', '1');
}

$template = new brdi_Portal_Page_Render();

echo $template->displayPage();
?>
