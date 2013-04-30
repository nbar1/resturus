<?php
$dbhost = 'nbar1.com';
$dbuser = 'thegogre_resturu';
$dbpass = 'N?$@C{fUV-ZX';
$dbname = 'thegogre_resturus';
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo $e->getMessage();
}
?>
