<?php
class yiggVimeoVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  /**
   * Returns a URL to the web accessible video page
   *
   * @param string $id Video ID
   * @return string URL to the web accessible video page
   */
  public static function getUrlForId($id)
  {
    return self::formatUrl('http://vimeo.com/%s', $id);
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
    return self::matchUrl($url, '/^http:\/\/(www.|)vimeo.com\/([0-9]{0,9})$/i', 2);
  }

  /**
   * returns the preview image for the video
   * @param String $id The youtube video id.
   */
  public function getPreviewImg()
  {
    return sprintf("http://ats.vimeo.com/%s/%s/%s_200.jpg",
        substr($this->getVideoId(), 0, 3),
        substr($this->getVideoId(), 3, 3),
        $this->getVideoId());
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
    $partial = new sfPartialView( sfContext::getInstance(), "video","_Vimeo","");
    $partial->setPartialVars(array(
      'video' => $this,
      'width' => isset($this->width) ? $this->width : 535,
      'height' => isset($this->height) ? $this->height : 400
    ));
    return $partial->render();
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggVimeoVideo');