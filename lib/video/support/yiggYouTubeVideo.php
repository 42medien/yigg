<?php

/**
 * YouTube video support
 *
 * @package    yigg
 * @subpackage video
 */
class yiggYouTubeVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  /**
   * Returns a URL to the web accessible video page
   *
   * @param string $id Video ID
   * @return string URL to the web accessible video page
   */
  public static function getUrlForId($id)
  {
    return self::formatUrl('http://www.youtube.com/watch?v=%s', $id);
  }

  /**
   * Extracts the ID of the video from the video URL
   *
   * @param string $url Video URL
   * @return mixed The video ID or NULL if the ID could not be extracted
   */
  public static function getIdForUrl($url)
  {
    return self::matchUrl($url, '/^(http[s]?:\/\/)?(www\.|de\.)?youtube\.com\/(watch\?)?v(\/|=)([^&\?\/]+).*$/i', 5);
  }

  /**
   * Returns a URL to the SWF playing the video
   *
   * @param string $id Video ID
   * @return string URL to the SWF playing the video
   */
  public static function getPlayerForId($id)
  {
    return self::formatUrl('http://de.youtube.com/v/%s', $id);
  }

  /**
   * returns the preview image for the video
   * @param String $id The youtube video id.
   */
  public function getPreviewImg()
  {
    return self::formatUrl('http://img.youtube.com/vi/%s/1.jpg', $this->getVideoId());
  }
  
  /**
   * Create a new instance for YouTube video support
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
    $partial = new sfPartialView( sfContext::getInstance(), "video","_Youtube","");
    $partial->setPartialVars(array(
      'video' => $this,
      'width' => isset($this->width) ? $this->width : 535,
      'height' => isset($this->height) ? $this->height : 400
    ));
    return $partial->render();
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggYouTubeVideo');