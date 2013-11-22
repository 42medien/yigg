<?php

use Mf2\Parser;

class yiggMetaDataExtractor {
  const TIMEOUT = 5;

  private $url,
          $response,
          $yiggMeta;

  public function __construct($url)
  {
    $this->url = $url;
    $this->loadData();
    $this->fetchData();
  }

  public function getReadableDescription()
  {
	  return $this->yiggMeta->getDescription();
  }

  public function getMetaTags()
  {
    return $this->yiggMeta->getTags();
  }

  public function getTitle()
  {
    return $this->yiggMeta->getTitle();
  }

  public function getImages()
  {
    return $this->yiggMeta->getImages();
  }

  /**
   * Fetches data from a remote server
   */
  private function loadData()
  {
    $ch = curl_init($this->url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, self::TIMEOUT);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_9_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/32.0.1664.3 Safari/537.36');

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

  /**
   * handles the parsing of a new social-object
   * currently parsed: opengraph and metatags
   *
   * @param string $pUrl
   * @return array $pArray
   */
  public function fetchData() {
    $media = new yiggExternalVideoSupport();

    if (!$this->response) {
      $this->yiggMeta = new YiggMeta();

      if (($provider = $media->match_url($this->url)) && !$this->yiggMeta->isComplete()) {
        //get the opengraph-tags
        $oEmbed = $media->fetch($provider, $this->url);
        $this->yiggMeta->fromOembed($oEmbed);
      }

      return;
    }

    $html = $this->response;
    $url = $this->url;

    // boost performance and use alreade the header
    $header = substr( $html, 0, stripos( $html, '</head>' ) );

    if (!$this->yiggMeta) {
      $this->yiggMeta = new YiggMeta();
    }

    $this->yiggMeta->setUrl($url);

    if ((preg_match('~http://opengraphprotocol.org/schema/~i', $header) || preg_match('~http://ogp.me/ns#~i', $header) || preg_match('~property=[\"\']og:~i', $header)) && !$this->yiggMeta->isComplete()) {
      //get the opengraph-tags
      $openGraph = OpenGraph::parse($html);
      $this->yiggMeta->fromOpenGraph($openGraph);
    }

    if (($provider = $media->match_url($url)) && !$this->yiggMeta->isComplete()) {
      //get the opengraph-tags
      $oEmbed = $media->fetch($provider, $url);
      $this->yiggMeta->fromOembed($oEmbed);
    } elseif ((preg_match('~application/(xml|json)\+oembed"~i', $header)) && !$this->yiggMeta->hasImages()) {
      try {
        $oEmbed = OEmbedParser::fetchByCode($header);
        $this->yiggMeta->fromOembed($oEmbed);
      } catch (Exception $e) {
        // catch exception and try to go on
      }
    }

    // parse meta tags
    if (!$this->yiggMeta->isComplete()) {
      $meta = @HtmlTagParser::getKeys($html, $url);


      sfContext::getInstance()->getLogger()->debug(print_r($meta, true));

      $this->yiggMeta->fromHtml($meta);
    }

    // parse microformats v2
    if (!$this->yiggMeta->isComplete()) {
      $parser = new Parser($html);
      $this->yiggMeta->fromMicroformats($parser->parse(), $url);
    }

    // add images
    $yiggImageParser = new ImageParser();
    if ($images = $yiggImageParser->detect($html, $url)) {
      $this->yiggMeta->setImages($images);
    }
  }
}