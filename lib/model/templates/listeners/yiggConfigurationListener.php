<?php
class yiggConfigurationListener extends Doctrine_Record_Listener
{
  /**
   * Excapes description and creates plain_text / summary
   */
  public function preSave( Doctrine_Event $event )
  {
    $invoker = $event->getInvoker();
    $invoker->prepareConfig();
  }
}