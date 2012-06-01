<?php

/**
 * Base helper class for external video support implementations
 *
 * @package    yigg
 * @subpackage video
 */
abstract class yiggExternalVideoSupport
{
  /**
   * Stores the URL to the web accessible video page
   *
   * @var string URL to the web accessible video page
   */
  private $videoUrl = null;

  /**
   * Stores the ID of the video
   *
   * @var string ID of the video
   */
  private $videoId = null;

  /**
   * Stores the URL to the SWF playing the video
   *
   * @var string URL to the SWF playing the video
   */
  private $playerUrl = null;

  /**
   * Tries to locate the ID of a video by appling a regular expression to it
   *
   * @param string $url The URL to match on
   * @param string $regex The regular expression to use
   * @param int $group_index The index of the result group in which the ID should be
   * @return mixed Returns the ID if the regular expression matched. NULL otherwise
   */
  protected static function matchUrl($url, $regex, $group_index)
  {
    if (0 == preg_match($regex, htmlspecialchars_decode($url), $groups))
    {
      return null;
    }

    return $groups[$group_index];
  }

  /**
   * Formats a URL just like sprintf
   * Override this function if you need to handle URLs differently
   *
   * @param string $format Format for sprintf
   * @param mixed $... Arguments for sprintf
   * @return string The Formated string
   */
  protected static function formatUrl($format)
  {
    $args = func_get_args();
    array_shift($args);

    return vsprintf ($format, $args);
  }

  /**
   * Set the URL to the web accessible video page
   *
   * @param string $value URL to the web accessible video page
   */
  protected function setVideoUrl($value)
  {
    $this->videoUrl = $value;
  }

  /**
   * Set the ID of the video
   *
   * @param string $value ID of the video
   */
  protected function setVideoId($value)
  {
    $this->videoId = $value;
  }

  /**
   * Set the URL to the SWF playing the video
   *
   * @param string $value URL to the SWF playing the video
   */
  protected function setPlayerUrl($value)
  {
    $this->playerUrl = $value;
  }

  /**
   * Initialize the object with a video ID
   *
   * @param string $id ID of a video
   * @return mixed Will return $this if the matching URL and SWF for the video ID could be found. NULL otherwise
   */
  public function initWithId($id)
  {
    $this->setVideoId($id);
    $this->setVideoUrl($this->getUrlForId($id));
    $this->setPlayerUrl($this->getPlayerForId($id));

    return $this;
  }

  /**
   * Initialize the object with a video URL
   *
   * @param string $url URL of the video
   * @return mixed Will return $this if the matching ID for this video URL could be found. NULL otherwise
   */
  public function initWithUrl($url)
  {
    if (null == ($id = $this->getIdForUrl($url)))
    {
      return null;
    }

    $this->setVideoId($id);
    $this->setVideoUrl($this->getUrlForId($id));
    $this->setPlayerUrl($this->getPlayerForId($id));

    return $this;
  }

  /**
   * Returns the ID of the video
   *
   * @return mixed ID of the video or NULL if not defined
   */
  public function getVideoId()
  {
    return $this->videoId;
  }

  /**
   * Returns the URL to the web accessible video page
   *
   * @return mixed URL to the web accessible video page or NULL if not defined
   */
  public function getVideoUrl()
  {
    return $this->videoUrl;
  }

  /**
   * Returns the URL to the SWF playing the video
   *
   * @return mixed URL to the SWF playing the video or NULL if not defined
   */
  public function getPlayerUrl()
  {
    return $this->playerUrl;
  }

  /**
   * Renders the video by calling the video/embed sfComponent
   *
   * @return string The rendered sfComponent
   */
  public function render()
  {
    $partial = new sfPartialView( sfContext::getInstance(), "video","_Embed","");
    $partial->setPartialVars(array('video' => $this, 'width' => isset($this->width) ? $this->width : 550, 'height' => isset($this->height) ? $this->height : 400));
    return $partial->render();
  }

  public function setWidthHeight($width=550, $height=400)
  {
    $this->width=$width;
    $this->height=$height;
    return $this;
  }
}