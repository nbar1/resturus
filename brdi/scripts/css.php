<?php
ob_start ("ob_gzhandler");
header("content-type: text/css; charset: UTF-8");
header("Cache-Control: max-age=1800");

/* your css files */
$css = json_decode(urldecode($_GET['load']));

// include globals
if(!isset($_GET['exclude_global'])) array_unshift($css, "config/default/assets/stylesheets/global/global.css");
if(!isset($_GET['exclude_bootstrap'])) array_unshift($css, "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css");
if(!isset($_GET['exclude_font'])) array_unshift($css, "http://fonts.googleapis.com/css?family=Prata");

foreach($css as $css_file)
{
	$old_css = $css_file;
	if(strpos($css_file, "http") !== 0)
	{
		$css_file = "../../" . $css_file;
	}
	echo "\n\n/** file: {$old_css} **/\n";

	echo str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', file_get_contents($css_file));
}
?>