<?php
class yiggMetaDataExtractor
{
  const TIMEOUT = 5;

  private $url,
          $response;

  public function __construct($url)
  {
    $this->url = $url;
    $this->loadData();
  }

  public function getReadableDescription()
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://boilerpipe-web.appspot.com/extract?url={$this->url}&extractor=ArticleExtractor&output=text");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);

    $response = curl_exec($ch);
    curl_close($ch);	

	  return $response;
  }

  public function getMetaTags()
 {
   preg_match_all('/<[\s]*meta[\s]*name="?' . '([^>"]*)"?[\s]*' . 'content="?([^>"]*)"?[\s]*[\/]?[\s]*>/si',
	   $this->response,
	   $matches,
	   PREG_PATTERN_ORDER);

	$metas = array();
	foreach($matches[1] as $key => $name)
	{
		$metas[$name] = $this->cleanValue($matches[2][$key]);
	}
	return $metas;
 }

  public function getTitle()
 {
   preg_match('/<title>([^>]*)<\/title>/si', $this->response, $match );
   return $this->cleanValue($match[1]);
 }

  /**
   * Fetches data from a remote server
   */
  private function loadData()
  {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);

    $this->response = curl_exec($ch);
    $content = iconv(mb_detect_encoding($this->response.'a', 'UTF-8, ISO-8859-1'),"UTF-8",$this->response);
    curl_close($ch);
  }

  private function cleanValue($value)
  {
    $value = html_entity_decode($value, ENT_QUOTES);
    $value = trim($value);
    $value = strip_tags($value);

    return $value;
  }
}

$extractor = new yiggMetaDataExtractor("http://www.howtogeek.com/howto/programming/php-get-the-contents-of-a-web-page-rss-feed-or-xml-file-into-a-string-variable/");
$extractor->getReadableDescription();