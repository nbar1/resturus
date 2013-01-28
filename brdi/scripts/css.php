<?php
header("Content-Style-Type: text/css");
$css = json_decode(urldecode($_GET['load']));

// include globals
array_unshift($css, "config/default/assets/stylesheets/global/global.css");
array_unshift($css, "http://netdna.bootstrapcdn.com/twitter-bootstrap/2.2.2/css/bootstrap-combined.min.css");
array_unshift($css, "http://fonts.googleapis.com/css?family=Prata");

foreach($css as $css_file)
{
	$old_css = $css_file;
	if(strpos($css_file, "http") !== 0)
	{
		$css_file = "/home1/thegogre/www/resturus/" . $css_file;
	}
	$css_output .=  "\n\n\n/** {$old_css} **/\n\n";
	$css_output .= file_get_contents($css_file);
}

$css_output = str_replace("}", "} ", $css_output);
echo $css_output;

?>