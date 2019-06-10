<?php

spl_autoload_register("BarcodeAutoloader::autoload");

class BarcodeAutoloader
{
	private static $autoloadPathArray = array(
		"lib/barcodegen/bar",
        "lib/barcodegen/bar/drawer",
	);
	
	public static function autoload($className)
	{
		foreach (self::$autoloadPathArray as $path) {
			$file = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR.$className.".php";
            $file = str_replace('\\', DIRECTORY_SEPARATOR, $file);
			if (is_file($file)) {
				include_once $file;
				break;
			}
		}
	}
	
	public static function addAutoloadPath($path)
	{
		array_push(self::$autoloadPathArray, $path);
	}
}

