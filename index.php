<?php
//ob_start ("ob_gzhandler");

define('STATUS','development');
define('INCLUDES','application/includes/');
define('CONFIG','config/');
require_once(INCLUDES."autoloader.class.php");
require_once('db.php');

$client = array();
$assets = array(
	'javascripts' => array(),
	'stylesheets' => array(),
);
$columns_at = 1;
$columns_max = 12;

if(isset($_GET['__e']) && $_GET['__e'] == true)
{
	ini_set('display_errors', '1');
}

if(isset($_REQUEST['__bc'])||isset($_REQUEST['__controller']))
{
	$bc_handler = new brdi_BackController();
	$bc_handler->process($_REQUEST);
	exit;
}

$template = new brdi_Portal_Page_Render();

echo $template->displayPage();
?>