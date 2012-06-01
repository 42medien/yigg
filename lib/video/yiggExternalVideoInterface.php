<?php

/**
 * Interface for external video support implementations
 *
 * @package    yigg
 * @subpackage video
 */
interface yiggExternalVideoInterface
{
  /**
   * Should return a URL to the web accessible video page matching the video ID
   *
   * @param string $id Video ID
   * @return string URL to the web accessible video page
   */
  public static function getUrlForId($id);

  /**
   * Should return a ID of the video matching the video URL
   *
   * @param string $url Video URL
   * @return mixed The video ID or NULL if no ID is available
   */
  public static function getIdForUrl($url);

  /**
   * Should return a URL to the SWF playing the video
   *
   * @param string $id Video ID
   * @return string URL to the SWF playing the video
   */
  public static function getPlayerForId($id);

  /**
   * Should return the ID of the video
   *
   * @return mixed The video ID or NULL if undefined
   */
  public function getVideoId();

  /**
   * Should return the URL to the web accessible video page of the video
   *
   * @return mixed The URL to the web accessible video page or NULL if undefined
   */
  public function getVideoUrl();

  /**
   * Should return the URL to the SWF playing the video
   *
   * @return mixed The URL to the SWF playing the video or NULL if undefined
   */
  public function getPlayerUrl();

  /**
   * Should return the embed code of the video
   *
   * @return string Embed code of the video
   */
  public function render();

  /**
   * Should initialize the object using the video ID
   *
   * @param string $id Video ID
   * @return mixed $this on success or NULL on failure
   */
  public function initWithId($id);

  /**
   * Should initialize the object using the video URL
   *
   * @param string $url Video URL
   * @return mixed $this on success or NULL on failure
   */
  public function initWithUrl($url);

  /**
   * Should return a new instance of the video class
   *
   * @return yiggExternalVideoInterface Video instance
   */
  public static function create();
}