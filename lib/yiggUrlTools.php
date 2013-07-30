<?php
class yiggUrlTools
{
  /**
   * Default options for curl.
   */
  public static $CURL_OPTS = array(
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_USERAGENT => 'yigg-http-client',
  );

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
	
  public static function do_get($url, $header = null) {
    $ch = curl_init();
    $opts = self::$CURL_OPTS;
    $opts[CURLOPT_URL] = $url;
    curl_setopt_array($ch, $opts);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);

    if ($header) {
      curl_setopt($ch, CURLOPT_HTTPHEADER,   $header);
    }

    $response = curl_exec($ch);

    return $response;
  }
  
  public static function do_post($url, $body, $header = null) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST,           1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,     $body);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HEADER,         0);  // DO NOT RETURN HTTP HEADERS
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  // RETURN THE CONTENTS OF THE CALL
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_VERBOSE,        0);

    if ($header) {
      curl_setopt($ch, CURLOPT_HTTPHEADER,   $header);
    }

    $response = curl_exec($ch);

    return $response;
  }
}