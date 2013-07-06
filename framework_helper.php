<?php
if($_SERVER['SERVER_ADDR'] == "198.199.81.142")
{
	define('STATUS','production');
}
else
{
	define('STATUS','development');
}
if(isset($override_constants))
{
	var_dump("lol");
	define('INCLUDES', $override_constants['INCLUDES']);
	define('CONFIG', $override_constants['CONFIG']);
	define('APPLICATION', $override_constants['APPLICATION']);
}
else
{
	define('INCLUDES','application/includes/');
	define('CONFIG','config/');
	define('APPLICATION', 'application/');
}
require_once(INCLUDES."autoloader.class.php");
require_once('db.php');

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
?>