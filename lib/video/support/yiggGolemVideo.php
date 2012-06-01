<?php
class yiggGolemVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  public static function getUrlForId($id)
  {
    return self::formatUrl('http://video.golem.de/games/%s', $id);
  }

  public static function getIdForUrl($url)
  {
    return self::matchUrl($url, '/http:\/\/video.golem.de\/.*\/([0-9]+)\/.*/i', 1);
  }

  public static function getPlayerForId($id)
  {
    return self::formatUrl('http://video.golem.de/player/videoplayer.swf?id=%s&autoPl=false', $id);
  }

  public static function create()
  {
    return new self;
  }

  public function render()
  {
    $partial = new sfPartialView( sfContext::getInstance(), "video","_golem","");
    $partial->setPartialVars(array(
      'video' => $this,
      'width' => isset($this->width) ? $this->width : 535,
      'height' => isset($this->height) ? $this->height : 400
    ));
    return $partial->render();
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggGolemVideo');