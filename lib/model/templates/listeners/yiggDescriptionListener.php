<?php
class yiggDescriptionListener extends Doctrine_Record_Listener
{
  /**
   * Excapes description and creates plain_text / summary
   */
  public function preSave( Doctrine_Event $event )
  {
    $record = $event->getInvoker();

    if(false === array_key_exists("description", $record->getModified()))
    {
      return;
    }

    if(true === $record->hasRelation("summary"))
    {
      $record->summary = htmlspecialchars(yiggTools::generateSummary($record->description));
    }

    if(true === $record->hasRelation("plain_text"))
    {
      $record->plain_text = htmlspecialchars(strip_tags($record->description));
    }

    $record->description = htmlspecialchars($record->description);
  }
}