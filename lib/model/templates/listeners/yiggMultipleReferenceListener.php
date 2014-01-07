<?php

/**
 * Yigg Tag listener, which prevents the deletion of tags.
 *
 */
class yiggMultipleReferenceListener extends Doctrine_Record_Listener
{
  /**
   * removes the references from the object.
   * (only load the references you wish to remove).
   */
  public function preDelete(Doctrine_Event $event)
  {
    // get the invoker and set the references
    $object = $event->getInvoker();
    $references = $object->getReferences();
    foreach($references as $reference)
    {
      $reference->delete();
    }
    $event->skipOperation();
  }
}