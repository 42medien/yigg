<?php
/**
 * this class provides functinality to receive and send private messages
 *
 */

class NotificationMessageTable extends Doctrine_Table
{
  /**
   * Transforms all the notification messages for this user to read.
   * 
   * @param  $user_id
   * @return Doctrine_Collection
   */
  public function markAllAsRead($user_id)
  {
    return $this->getQueryObject()
          ->update('NotificationMessage')
          ->set('read_at', 'NOW()')
          ->where('recipient_id = ?', $user_id)
          ->addWhere('read_at IS NULL')
          ->execute();
  }

  /**
   * Takes a collection and marks them all as read.
   * @return Doctrine_Collection
   */
  public function markAsRead($collection)
  {
    $conn = $this->getConnection();
    $conn->beginTransaction();

    foreach($collection as $msg)
    {
        if(empty($msg->read_at))
        {
            $msg->read_at = yiggTools::getDbDate();
            $msg->save($conn);
        }
    }

    return $conn->commit();
  }
}