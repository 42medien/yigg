<?php

/**
 * implements editable behaviour for yigg
 * on creation, created_at will be set automatically to now()
 * on update, if available last_edited will be changed/set to now()
 *
 * @package     yigg
 * @subpackage  helpers
 */
class yiggEditableBehaviour extends Doctrine_Record_Listener
{
  /**
   * insert current date before updating entry
   *
   * @param 	Doctrine_Event	$event
   * @return 	void
   */
  public function preUpdate(Doctrine_Event $event)
  {
    parent::preUpdate($event);
    $record = $event->getInvoker();
    if( true == $record->contains('last_edited') ) {
      $record->last_edited = yiggTools::getDbDate();
    }
  }

  /**
   *
   *
   * @param Doctrine_Event $event
   */
  public function preSave(Doctrine_Event $event)
  {
    parent::preUpdate($event);
    $record = $event->getInvoker();
    if( true == $record->contains('created_at') ) {
      $record->created_at = yiggTools::getDbDate();
    }
  }
}
