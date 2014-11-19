<?php

class StringUtils
{
	public static function substringAfter($str, $needle)
	{
		$startpos = strpos($str, $needle) + strlen($needle);
		return substr($str, $startpos, strlen($str) - $startpos);
	}
	
	public static function substringAfterLast($str, $needle)
	{
		$startpos = strrpos($str, $needle) + strlen($needle);
		return substr($str, $startpos, strlen($str) - $startpos);
	}
	
	public static function substringBefore($str, $needle)
	{
		return substr($str, 0, strpos($str, $needle));
	}
	
	public static function substringBeforeLast($str, $needle)
	{
		return substr($str, 0, strrpos($str, $needle));
	}
	
	public static function substringAfterFirstOf($str, $charlist)
	{
		for ($si = 0;$si < strlen($str);$si++)
		{
			for ($i = 0;$i < strlen($charlist);$i++)
			{
				if ($str[$si] == $charlist[$i])
				{
					return substr($str, $si + 1, strlen($str) - $si - 1);
				}
			}
		}
		return "";
	}
	
	public static function substringBeforeFirstOf($str, $charlist)
	{
		for ($si = 0;$si < strlen($str);$si++)
		{
			for ($i = 0;$i < strlen($charlist);$i++)
			{
				if ($str[$si] == $charlist[$i])
				{
					return substr($str, 0, $si);
				}
			}
		}
		return "";
	}
	
	public static function startsWith($str, $needle)
	{
		return substr($str, 0, strlen($needle)) == $needle;
	}
	
	public static function endsWith($str, $needle)
	{
		$needleSize = strlen($needle);
		return substr($str, strlen($str) - $needleSize, $needleSize) == $needle;
	}
	
	public static function startsWithChar($str, $charlist)
	{
		if (strlen($str) == 0)
		{
			return false;
		}
		for ($i = 0;$i < strlen($charlist);$i++)
		{
			if ($str[0] == $charlist[$i])
				return true;
		}
		return false;
	}
	
	public static function contains($haystack, $needle)
	{
		return strpos($haystack, $needle) !== false;
	}
	
	public static function containsOneOf($haystack, $charlist)
	{
		if (strlen($haystack) == 0)
			return false;
		for ($i = 0;$i < strlen($charlist);$i++)
		{
			if (strpos($haystack, $charlist[$i]) !== false)
				return true;
		}
		return false;
	}
}

?>