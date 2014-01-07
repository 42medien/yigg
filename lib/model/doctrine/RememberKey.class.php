<?php

/**
 *
 * @package     yigg
 * @subpackage  user
  */
class RememberKey extends BaseRememberKey
{
    /**
     * Creates and returns a new Remember key for the user, based on the time.
     * We set Created time manually, as it's used in the salt of the username
     * for the cookie. ( we check this and the ip for the remember key )
     */
  static function create( $user )
  {
    $rememberKey = new self();
    $time = time();
    $rememberKey->remember_key  = (md5( rand(1000, $time ) . $user->username));
    $rememberKey->ip_address  = $user->last_ip;
    $rememberKey->created_at = yiggTools::getDbDate( null, $time );
    $rememberKey->user_id    = $user->id;
    $rememberKey->save();
    return $rememberKey;
  }
}