<?php
require_once 'util/stringutils.php';

class Template
{	
	private $template;
	
	private $style;
	
	private $theme;
	
	private $variables = array();
	
	private $developmode = false;
	
	public function __construct($template, $style, $theme)
	{
		$this->template = $template;
		$this->style = $style;
		$this->theme = $theme;
		$this->variables["THEME"] = $theme;
		$this->variables["SCRIPTLOCATION"] = "styles/$this->style/scripts";
	}
	
	public function setDevelopMode($developmode)
	{
		$this->developmode = $developmode;
	}
	
	public function setVariable($name, $value)
	{
		$this->variables[$name] = $value;
	}
	
	public function setVariables($array)
	{
		$this->variables = array_merge($variables, $array);
	}
	
	public function addLineVars($linename, $value)
	{
		$this->variables[$linename][] = $value;
	}
	
	public function addStyleFile($filename, $media = "")
	{
		$this->variables["L_STYLES"][] = array("LOCATION" => "styles/$this->style/themes/$this->theme/$filename.css",
											   "MEDIA" => $media);
	}
	
	public function addScriptFile($filename, $params = null)
	{
		$array = array("LOCATION" => "styles/$this->style/scripts/$filename.js");
		if (isset($params))
			$array["PARAMS"] = $params;
		$this->variables["L_SCRIPTS"][] = $array;
	}
	
	public function sendPage()
	{
		$v = $this->variables;
		require Template::getTemplateLocation($this->template, $this->style, $this->theme);
	}
	
	public static function getTemplateLocation($template, $style, $theme)
	{
		$templateLocation = "cache/$style/$template.php";
		if (!file_exists($templateLocation) || $this->developmode)
		{
			Template::generateTemplate($template, $style, $theme);
		}
		return $templateLocation;
	}
	
	public static function generateTemplate($template, $style, $theme)
	{
		@mkdir("cache/$style", 0777, true);
		$pattern = fopen("styles/$style/templates/$template.html", "r");
		$result = fopen("cache/$style/$template.php", "w");
		
		while (!feof($pattern))
		{
			$c = fread($pattern, 1);
			if ($c == "<")
			{
				$str = $c;
				$c = fread($pattern, 1);
				if ($c != "!")
				{
					fwrite($result, $str.$c);
					continue;
				}
				$str .= $c;
				$c = fread($pattern, 1);
				if ($c != "-")
				{
					fwrite($result, $str.$c);
					continue;
				}
				$str .= $c;
				$c = fread($pattern, 1);
				if ($c != "-")
				{
					fwrite($result, $str.$c);
					continue;
				}
				$str .= $c;
				do
				{
					$str .= fread($pattern, 1);
				} while (!StringUtils::endsWith($str, "-->"));
				$command = StringUtils::substringAfter($str, "<!--");
				$command = StringUtils::substringBefore($command, "-->");
				$command = trim($command);
				if (StringUtils::startsWith($command, "INCLUDE"))
				{
					$command = StringUtils::substringAfter($command, "INCLUDE ");
					$templateLocation = Template::getTemplateLocation($command, $style, $theme);
					fwrite($result, "<?php require '$templateLocation';?>");
				}
				else if (StringUtils::startsWith($command, "IF"))
				{
					$command = StringUtils::substringAfter($command, "IF ");
					$command = Template::replaceVars($command);
					fwrite($result, "<?php if($command){?>");
				}
				else if (StringUtils::startsWith($command, "ELSE IF"))
				{
					$command = StringUtils::substringAfter($command, "ELSE IF ");
					$command = Template::replaceVars($command);
					fwrite($result, "<?php }else if($command){?>");
				}
				else if (StringUtils::startsWith($command, "ELSE"))
				{
					fwrite($result, "<?php }else{?>");
				}
				else if (StringUtils::startsWith($command, "FOREACH"))
				{
					$command = StringUtils::substringAfter($command, "FOREACH ");
					$command = Template::replaceVars($command);
					$array = StringUtils::substringBefore($command, " ");
					fwrite($result, "<?php if(isset($array))foreach($command){?>");
				}
				else if (StringUtils::startsWith($command, "END"))
				{
					fwrite($result, "<?php }?>");
				}
				else
				{
					fwrite($result, $str);
				}
			}
			else if ($c == "{")
			{
				$str = $c;
				do
				{
					$c = fread($pattern, 1);
					$str .= $c;
				} while ($c != "}");
				$varname = substr($str, 1, strlen($str) - 2);
				fwrite($result, "<?php echo ".Template::replaceEchoVars($varname).";?>");
			}
			else
			{
				fwrite($result, $c);
			}
		}
		
		fclose($pattern);
		fclose($result);
	}
	
	private static function replaceVars($str)
	{
		$result = "";
		while ($str != "")
		{
			if (StringUtils::containsOneOf($str, " ()"))
			{
				$part = StringUtils::substringBeforeFirstOf($str, " ()");
			}
			else
			{
				$part = $str;
			}
			if (StringUtils::startsWithChar($part, "ABCDEFGHIJKLMNOPQRSTUVWXYZ"))
			{
				if (StringUtils::contains($part, "."))
				{
					$arrayname = StringUtils::substringBefore($part, ".");
				$varname = StringUtils::substringAfter($part, ".");
				$result .= '$v["'.$arrayname.'"]["'.$varname.'"]';
				}
				else 
				{
					$result .= '$v["'.$part.'"]';
				}
			}
			else
			{
				$result .= $part;
			}
			if ($str != $part)
				$result .= $str[strlen($part)];
			$str = StringUtils::substringAfterFirstOf($str, " ()");
		}
		return $result;
	}
	
	private static function replaceEchoVars($str)
	{
		$result = "";
		while ($str != "")
		{
			if (StringUtils::containsOneOf($str, " ()"))
			{
				$part = StringUtils::substringBeforeFirstOf($str, " ()");
			}
			else
			{
				$part = $str;
			}
			if (StringUtils::startsWithChar($part, "ABCDEFGHIJKLMNOPQRSTUVWXYZ"))
			{
				$arrayparts = explode(".", $part);
				$array = '$v';
				foreach ($arrayparts as $apart)
				{
					$array .= '["'.$apart.'"]';
				}
				$result .= "isset($array)?$array:\"{$part}\"";
			}
			else
			{
				$result .= $part;
			}
			if ($str != $part)
				$result .= $str[strlen($part)];
			$str = StringUtils::substringAfterFirstOf($str, " ()");
		}
		return $result;
	}
}
?>