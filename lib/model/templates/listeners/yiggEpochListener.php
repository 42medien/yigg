<?php
class yiggEpochListener extends Doctrine_Record_Listener
{
  /**
   * Excapes description and creates plain_text / summary
   */
  public function preInsert( Doctrine_Event $event )
  {
    $record = $event->getInvoker();
    $record->epoch_time = yiggTimeTools::getCurrentEpochTime();
  }
}