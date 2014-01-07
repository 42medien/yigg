<?php
class yiggConfigurable extends Doctrine_Template
{
  protected $cache;
  
  public function getConfig()
  {
    if(empty($this->cache))
    {
      $this->cache = new sfParameterHolder();
    }
    $invoker = $this->getInvoker();
    $obj_key = get_class($invoker).$invoker->id;

    if(false === $this->cache->has($obj_key))
    {     
      $this->cache->set($obj_key, new sfNamespacedParameterHolder("profile"));
      $this->cache->get($obj_key)->unserialize($invoker->configuration);
    }
    return $this->cache->get($obj_key);
  }
  
  public function prepareConfig()
  {
    $invoker = $this->getInvoker();
    $invoker->configuration = $this->getConfig()->serialize();
  }
  
  public function setTableDefinition()
  {
    $this->addListener( new yiggConfigurationListener() );
  }
}