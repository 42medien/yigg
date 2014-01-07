<?php

class bitLyApi
{
  const API_HOST = "http://api.bit.ly/";
  const API_KEY  = "R_56d88382549835b92388510dcee8874a";
  const LOGIN    = "rocu";
  const VERSION  = "2.0.1";
  
  function __call($method, $params)
  {
   	$params = array_merge($params[0], array("login" => self::LOGIN, "apiKey" => self::API_KEY, "version" => self::VERSION));
	$url = self::API_HOST.$method."?".http_build_query($params);
    $ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mug.im-Bot");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$response = curl_exec($ch);
	curl_close($ch);
	
	return json_decode($response)->results;

  }
}

?>