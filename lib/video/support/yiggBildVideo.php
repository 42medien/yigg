<?php
class yiggBildVideo extends yiggExternalVideoSupport implements yiggExternalVideoInterface
{
  public static function getUrlForId($id)
  {
    return $id.",view=xml,autoplay=false.bild.xml";
  }

  public static function getIdForUrl($url)
  {
    preg_match("/http:\/\/www.bild.de\/video\/clip/", $url, $match);
    if(count($match) == 0)
    {
      return null;
    }
    $id = explode(",", $url);
    if(false === array_key_exists(0, $id))
    {
      return null;
    }
    return $id[0];
  }

  public static function getPlayerForId($id)
  {
    return $id;
  }

  public static function create()
  {
    return new self;
  }

  public function render()
  {
    $partial = new sfPartialView( sfContext::getInstance(), "video","_bild","");
    $partial->setPartialVars(array(
      'video' => $this,
      'width' => isset($this->width) ? $this->width : 535,
      'height' => isset($this->height) ? $this->height : 400
    ));
    return $partial->render();
  }
}

yiggExternalVideoFactory::registerFactoryClass('yiggBildVideo');