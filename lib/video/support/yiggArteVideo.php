<?php
class yiggArteVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  /**
   * Returns a URL to the web accessible video page
   *
   * @param string $id Video ID
   * @return string URL to the web accessible video page
   */
  public static function getUrlForId($id)
  {
    return self::formatUrl('http://videos.arte.tv/de/videos/-%s.html', $id);
  }

  public static function getPlayerForId($id)
  {

  }

  /**
   * Extracts the ID of the video from the video URL
   *
   * @param string $url Video URL
   * @return mixed The video ID or NULL if the ID could not be extracted
   */
  public static function getIdForUrl($url)
  {
    return self::matchUrl($url, '/^http:\/\/videos.arte.tv\/de\/videos\/[a-z0-9_]{3,60}-([0-9]{2,7}).html$/i', 1);
  }

  /**
   * Create a new instance for Vimeo video support
   *
   * @return yiggExternalVideoInterface Video support instance
   */
  public static function create()
  {
    return new self;
  }

  /*
   * Renders the video by calling the video/embed sfComponent
   *
   * @return string The rendered sfComponent
   */
  public function render()
  {
    $partial = new sfPartialView( sfContext::getInstance(), "video","_arte","");
    $partial->setPartialVars(array(
      'video' => $this,
      'width' => isset($this->width) ? $this->width : 535,
      'height' => isset($this->height) ? $this->height : 400
    ));
    return $partial->render();
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggArteVideo');