<?php

/**
 *
 * @package     yigg
 * @subpackage  user
  */
class ResetPasswordKey extends BaseResetPasswordKey
{
  /**
   * Creates and returns a new ResetPassword key for the user, based on the
   * time and username. ( stops cracking / brute force )
   *
   */
  public static function create( $user )
  {
    //Remove any outstanding keys for this user

    $key = new self();
    $key->cleanup( $user );
    $key->user_id    = $user->id;
    $time = time();
    $key->reset_key  = md5( rand(100000, $time ) . $user->username );
    $key->expires    = yiggTools::getDbDate( null, $time + 60*60*12); // 12 hours
    $key->save();
    return $key;
  }

  /**
   *
   */
  public function cleanup( $user )
  {
    $results = ResetPasswordKeyTable::findByUser( $user );
    foreach($results as $result)
    {
      $result->delete();
    }
  }

  /**
   * Will let you know if the key has expired already.
   * @return boolean isExprired
   */
  public function isExpired()
  {
    return  time() > strtotime( $this->expires );
  }

  /**
   * Automatically sets the association as the currently logged in user,
   * and sets the datetime of the event.
   *
   * @param unknown_type $event
   */
  public function preInsert( $event )
  {
    parent::preInsert($event);
    $this->created_at = yiggTools::getDbDate();
  }
}