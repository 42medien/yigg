<?php

/**
 * @package         yigg
 * @subpackage   user
 */
class UserFollowing extends BaseUserFollowing
{

  /**
   * Send notifications about this the new Follower to the Followed user
   */
  public function processNotifications()
  {
    $conn = Doctrine::getConnectionByTableName("User");
    Doctrine::getTable("User")->findOneById($this->following_id)
    ->notify(
      $this,
      "UserFollower",
      true,
      $conn
    );
  }
}