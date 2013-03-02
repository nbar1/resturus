<?php
$template = "!{token://this/is/test/}     sdfg sg!{html://this/is/not/text/ate}";
preg_match_all("|!{(.+?)://([A-Za-z0-9/_]+)/?}|", $template, $matches);
foreach($matches[2] as $k=>$v)
{
	$matches[2][$k] = explode("/", $v);
}
var_dump($matches);

/*
$content = array(
	'hello' => true,
	'nope' => false,
	'this' => array(
		'is'=> array(
			'a' => array(
				'test' => "test!!!!",
			),
		),
	),
);

$type = "this/is/a/test";

$path = explode("/", $type);
$array = "\$content";
for($i=0; $i<sizeof($path); $i++)
{
	$array .= "['".$path[$i]."']";
}
eval("\$array = ".$array.";");
var_dump($array);	
*/
?>