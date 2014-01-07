<?php

class yiggWebResponse extends sfWebResponse
{
  public function setTitle($title, $escape = true)
  {
    parent::setTitle(sprintf('%s - YiGG.de', $title), $escape);
  }
  
  /**
   * Store this response in Squid
   */
  public function storeInSquidCache($seconds=300)
  {
    $this->setHttpHeader('Cache-Control', "maxage=$seconds");
    $this->setHttpHeader('Expires',  gmdate('D, d M Y H:i:s', time()+$seconds) . ' GMT+1', false);
  }
}