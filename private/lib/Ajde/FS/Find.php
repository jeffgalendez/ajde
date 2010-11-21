<?php

class Ajde_FS_Find extends Ajde_Object_Static
{
	public static function findFile($dir, $pattern)
	{
		$search = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir . $pattern;
		foreach (glob($search) as $filename) {
			return $filename;
		}
		return false;
	}
	
	public static function findFiles($dir, $pattern)
	{
		$search = $_SERVER['DOCUMENT_ROOT'] . '/' . $dir . $pattern;
		$return = array();
		foreach (glob($search) as $filename) {
			$return[] = $filename;
		}
		return $return;
	}
}