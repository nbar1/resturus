<?php
define('STATUS','development');
define('INCLUDES','application/includes/');
define('CONFIG','config/');
require_once(INCLUDES."autoloader.class.php");
require_once('db.php');

$bc_handler = new brdi_BackController($_REQUEST);
?>