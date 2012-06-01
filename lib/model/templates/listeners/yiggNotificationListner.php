<?php
class yiggNotifcationListener extends Doctrine_Record_Listener
{
  public function postInsert( Doctrine_Event $event )
  {
    $record = $event->getInvoker();
    return $record->processNotifications();
  }
  
  /**
   * Deletes Notifications if the referenced record gets deleted
   */
  public function preDelete( Doctrine_Event $event )
  {
	$event->getInvoker()->deleteAssociatedNotifications();
  }
}