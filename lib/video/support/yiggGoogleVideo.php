<?php

/**
 * Google video support
 *
 * @package    yigg
 * @subpackage video
 */
class yiggGoogleVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  /**
   * Returns a URL to the web accessible video page
   *
   * @param string $id Video ID
   * @return string URL to the web accessible video page
   */
  public static function getUrlForId($id)
  {
    return self::formatUrl('http://video.google.de/videoplay?docid=%s', $id);
  }

  /**
   * Extracts the ID of the video from the video URL
   *
   * @param string $url Video URL
   * @return mixed The video ID or NULL if the ID could not be extracted
   */
  public static function getIdForUrl($url)
  {
    return self::matchUrl($url, '/^(http[s]?:\/\/)?video\.(l\.)?google\.[a-z]+\/(videoplay|googleplayer\.swf)\?docid=([^&\?\/]+).*$/i', 4);
  }

  /**
   * Returns a URL to the SWF playing the video
   *
   * @param string $id Video ID
   * @return string URL to the SWF playing the video
   */
  public static function getPlayerForId($id)
  {
    return self::formatUrl('http://video.google.de/googleplayer.swf?docid=%s', $id);
  }

  /**
   * Create a new instance for Google video support
   *
   * @return yiggExternalVideoInterface Video support instance
   */
  public static function create()
  {
    return new self;
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggGoogleVideo');