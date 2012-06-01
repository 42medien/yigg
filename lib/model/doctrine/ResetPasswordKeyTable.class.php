<?php

/**
 *
 * @package     yigg
 * @subpackage  user
  */
class ResetPasswordKeyTable extends Doctrine_Table
{
  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable('ResetPasswordKey');
  }

  public static function findByUser($user)
  {
    return self::getTable()->findBy( $column = 'user_id', $user->id );
  }

  public static function findByKey($key)
  {
    return self::getTable()->findOneByResetKey($key);
  }
}