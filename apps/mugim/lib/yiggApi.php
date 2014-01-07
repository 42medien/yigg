<?php

class yiggApi
{
  public static function getYiggs($url)
  {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,"http://yigg.de/GetYiggs?exturl=".urlencode($url));
	curl_setopt($ch, CURLOPT_USERAGENT, "Mug.im / YiGG-Checker");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	
	$response = curl_exec($ch);
	curl_close($ch);
	
	return intval($response);
  }
}

?>