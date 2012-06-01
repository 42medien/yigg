<?php
class yiggUrlTools
{
	/**
	 * Encode umlauts from url-path
	 */
	public static function encodeUmlauts($url)
	{
		$parts = parse_url($url);
		
		$replacements = array(
			"/ä/" => "%C3%A4",
			"/ü/" => "%C3%BC",
			"/ö/" => "%C3%B6");
		
		if(false === array_key_exists("path", $parts))
		{
			return $url;
		}
		
		$parts["path"] = preg_replace(array_keys($replacements), $replacements, $parts["path"]);
		
		if(false === array_key_exists("scheme", $parts))
		{
			$parts["scheme"] = "http";
		}
		if(false === array_key_exists("path", $parts))
		{
			$parts["path"] = "";
		}
		$encoded_url = sprintf("%s://%s%s", $parts["scheme"], $parts["host"], $parts["path"]);
		
		if(array_key_exists("query", $parts))
		{
			$encoded_url .= "?".$parts["query"];
		}
		return $encoded_url;
	}
	
	/**
	 * Fetches title for an url
	 * @param $url
	 * @return String Title
	 */
	
	public static function fetchTitle($url)
	{
	  try
	  {
	    preg_match( "/<title>(.*)<\/title>/si", file_get_contents($url), $match );
	  }
	  catch(Exception $e)
	  {
	    return;
	  }
	  if(empty($match[1]))
	  {
	    return;
	  }
	  return trim(strip_tags($match[1])); 
	}
	
}