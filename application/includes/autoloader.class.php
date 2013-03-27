<?php
spl_autoload_register(array('Autoloader', 'autoload'));
class Autoloader
{
	public static function autoload($class)
	{
		if (class_exists($class, FALSE))
		{
			return TRUE;
		}
		$class_path = str_replace("_", "/", $class);
		$file = "application/" . $class_path . '.php';
		if(!file_exists($file))
		{
			return false;
			exit;
		}
		require_once($file);
		unset($file);

		if(!class_exists($class, FALSE))
		{
			echo 'The requested class was not found: ' .$class;
			exit;
		}
	}
}
?>
