<?php
/**
 *
 * @package     yigg
 * @subpackage  user
  */
class UserPermissionTable extends Doctrine_Table
{
  /**
   * Get highest permission level by user_id
   * @param int user_id
   * @return int Highest Permission level
   */
  public static function getHighestPermissionLevel($user_id)
  {
    $query = Doctrine_Query::create();
      $query	->select('permission_level')
              ->from('UserPermission')
              ->where('user_id = ?', $user_id)
              ->orderBy('permission_level DESC')
              ->limit(1);
        $result = $query->fetchOne();
        return (isset($result->permission_level))? $result->permission_level : 0;
  }
}
