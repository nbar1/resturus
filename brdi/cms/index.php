<?php
session_start();
//if($_GET['__e'] == true)
//{
		ini_set('display_errors', '1');
//}
require_once('../../db.php');
require_once("../includes/autoloader.class.php");
	
$action = (isset($_GET['action']))?$_GET['action']:"dashboard";
$action_base = explode("/", $action);
$action_base = $action_base[0];

if(!isset($_SESSION['client']))
{
	$action = "login";
}

ob_start();
if(file_exists("actions/".$action.".php"))
{
	include("actions/".$action.".php");
}
else {
	echo "Invalid action: {$action}";
}
$cms_page_html = ob_get_clean();
?>
<!doctype html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" type="text/stylesheet" href="/brdi/scripts/css.php?exclude_global=true" />
<link rel="stylesheet" type="text/stylesheet" href="cms.css" />
<script src="http://code.jquery.com/jquery-latest.js"></script>
<script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
<script src="//netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/js/bootstrap.min.js"></script>
</head>
<body>
<div id="wrapper" class="container-fluid">
<?php echo $cms_page_html; ?>
</div>
</body>
</html>