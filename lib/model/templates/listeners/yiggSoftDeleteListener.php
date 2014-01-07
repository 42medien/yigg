<?php
/**
 * YiGGListener for SoftDelete behavior which will allow you to turn on the behavior which
 * sets a delete flag instead of actually deleting the record and all queries automatically
 * include a check for the deleted flag to exclude deleted records.
 */
class yiggSoftDeleteListener extends Doctrine_Template_Listener_SoftDelete
{

  /**
   * Skip the normal delete options so we can override it with our own
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preDelete(Doctrine_Event $event)
  {
    $event->skipOperation();
  }


  /**
   *  Stops the deletion from occurring.
   */
  public function postDelete( Doctrine_Event $event)
  {
    // save the delete.
    $record = $event->getInvoker();
    $field = $this->_options['deleted']['name'];
    $record->$field = $this->getTimestamp();
    $result = $event->getInvoker()->save();
  }

  /**
   * Implement preDqlDelete() hook and add the deleted flag to all queries for which this model
   * is being used in.
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preDqlSelect( Doctrine_Event $event)
  {
    $params = $event->getParams();
    $field = $params['alias'] . '.' . $this->_options['deleted']['name'];
    $query = $event->getQuery();
    if ( ! $query->contains($field))
    {
     $query->addWhere($field . ' IS NULL');
    }
  }

  /**
   * Implement preDqlDelete() hook and modify a dql delete query so it updates the deleted flag
   * instead of deleting the record
   *
   * @param Doctrine_Event $event
   * @return void
   */
  public function preDqlDelete(Doctrine_Event $event)
  {
    $params = $event->getParams();
    $field = $params['alias'] . '.' . $this->_options['deleted']['name'];
    $query = $event->getQuery();
    if ( ! $query->contains($field))
    {
      $query->from('')->update( $params['component']['table']->getOption('name') . ' ' . $params['alias']);
      $query->set($field,'?', array($this->getTimestamp()));
    }
  }

  /**
   * Gets the timestamp in the correct format based on the way the behavior is configured
   *
   * @param string $type
   * @return void
   */
  public function getTimestamp()
  {
    if ($this->_options['deleted']['expression'] !== false && is_string($this->_options['deleted']['expression']))
    {
      return new Doctrine_Expression($options['expression']);
    }
    else
    {
      if ($this->_options['deleted']['type'] == 'date')
      {
        return date($options['format'], time());
      }
      else if ($this->_options['deleted']['type'] == 'timestamp')
      {
        return date($this->_options['deleted']['format'], time());
      }
      else
      {
        return time();
      }
    }
  }
}