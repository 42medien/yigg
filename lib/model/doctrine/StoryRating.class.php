<?php

/**
 *
 * @package     yigg
 * @subpackage  story
  */
class StoryRating extends BaseStoryRating
{
  /**
   * Automatically sets the association as the currently logged in user if empty
   * and sets the datetime of the event.
   *
   * @param unknown_type $event
   */
  public function preInsert( $event )
  {
    parent::preInsert($event);
    $userSession = sfContext::getInstance()->getUser();
    if(empty($this->user_id))
    {
      $this->user_id = (int) $userSession->hasUser() ? $userSession->getUser()->id : 1;
    }
  }
}