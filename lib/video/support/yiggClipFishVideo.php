<?php

/**
 * ClipFish video support
 *
 * @package    yigg
 * @subpackage video
 */
class yiggClipFishVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  /**
   * Returns a URL to the web accessible video page
   *
   * @param string $id Video ID
   * @return string URL to the web accessible video page
   */
  public static function getUrlForId($id)
  {
    return self::formatUrl('http://www.clipfish.de/player.php?videoid=%s', $id);
  }

  /**
   * Extracts the ID of the video from the video URL
   *
   * @param string $url Video URL
   * @return mixed The video ID or NULL if the ID could not be extracted
   */
  public static function getIdForUrl($url)
  {
    return self::matchUrl($url, '/^(http[s]?:\/\/)?(www\.)?clipfish\.de\/(player\.php|videoplayer\.swf)\?.*videoid=([^&\?\/]+).*$/i', 4);
  }

  /**
   * Returns a URL to the SWF playing the video
   *
   * @param string $id Video ID
   * @return string URL to the SWF playing the video
   */
  public static function getPlayerForId($id)
  {
    return self::formatUrl('http://www.clipfish.de/videoplayer.swf?videoid=%s', $id);
  }

  /**
   * Create a new instance for ClipFish video support
   *
   * @return yiggExternalVideoInterface Video support instance
   */
  public static function create()
  {
    return new self;
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggClipFishVideo');