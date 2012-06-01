<?php
class UserFollowingTable extends Doctrine_Table
{

	/**
	 * Returns query that returns online followed presons
	 * @param $user_id
	 * @return Doctrine_Query
	 */

  public static function getOnlineFollowedUsers($user_id)
  {
    return Doctrine_Query::create()
      ->from('User u')
      ->innerJoin('u.UserOnlineLog uol')
      ->leftJoin('u.Followers uf')
      ->where('last_login > DATE_SUB(NOW(), INTERVAL 2 DAY)')
      ->addWhere('uol.created_at > DATE_SUB(NOW(), INTERVAL 120 MINUTE)')
      ->addWhere('uf.id = ?', $user_id)
      ->execute();
  }

  public static function getFollowedUsersQueryByUserId($user_id)
  {
    return Doctrine_Query::create()
      ->select('u.*, ua.*')
      ->from('User u')
      ->leftJoin('u.Followers uf')
      ->leftJoin('u.Avatar ua')
      ->addWhere('uf.id = ?', $user_id)
      ->orderBy("u.last_login DESC");
  }

  public static function getFollowingUsersQueryByUserId($user_id)
  {
    return Doctrine_Query::create()
      ->select('u.*, ua.*')
      ->from('User u')
      ->leftJoin('u.Following uf')
      ->leftJoin('u.Avatar ua')
      ->addWhere('uf.id = ?', $user_id)
      ->orderBy("u.last_login DESC");
  }
}