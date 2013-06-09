<?php
$dbhost = 'localhost';
$dbuser = 'resturus_db_all';
$dbpass = 'VBLzD9fxYGLhNfGH';
$dbname = 'resturus';
try
{
	$db = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
	echo $e->getMessage();
}
?>
