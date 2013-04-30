<?php
header("content-type: text/css; charset: UTF-8");
header("Cache-Control: max-age=1800");

/* your css files */
$css = json_decode(urldecode($_GET['load']));

if(!is_array($css)) $css = array();

// include globals
if(!isset($_GET['exclude_global'])) array_unshift($css, "config/default/assets/stylesheets/global/global.css");
if(!isset($_GET['exclude_bootstrap'])) array_unshift($css, "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.3.1/css/bootstrap-combined.min.css");
if(!isset($_GET['exclude_font_prata'])) array_unshift($css, "http://fonts.googleapis.com/css?family=Prata");
if(!isset($_GET['exclude_font_roboto'])) array_unshift($css, "http://fonts.googleapis.com/css?family=Roboto+Condensed:400,300");

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