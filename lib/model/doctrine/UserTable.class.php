<?php
/**
 *
 * @package     yigg
 * @subpackage  user
  */
class UserTable extends Doctrine_Table
{

  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable("User");
  }

  public function getTwitterUsers()
  {
    $query = new Doctrine_Query();
    $query ->from("User u")
           ->where("u.twitter_name IS NOT NULL");
    return $query->execute();
  }

  /**
   * Returns the top 5 most successful speculators.
   *
   * @return object User object
   */
  public static function getMostSuccessfulSpeculators()
  {
    // create Doctrine Object
    $query = new Doctrine_Query();
    $query ->select('u.yipps')
              ->from('User u')
              ->orderBy('u.yipps DESC')
              ->limit(5);
    return $query->execute();
  }

    /**
     * Returns all the genders we have for the enumeration
     */
  static public function getAllGenders()
  {
    return self::$Gender_NAMES;
  }

  /**
   * returns boolean if the user is deleted.
   * @param $username (string|int)
   * @return Boolean
   */
  public static function isDeleted($unique)
  {
    // check to see if the user was deleted already.
    $query =  "SELECT deleted_at FROM user WHERE ". (true == is_int($unique) ? "id" : "username") ." = ?";
    $connection = Doctrine::getConnectionByTableName("User");
    $results = $connection->execute($query, array($unique) );
    $result = $results->fetchAll();
    if( is_array($result) && count($result) > 0 && (int) strtotime($result[0]['deleted_at']) > 0 )
    {
      return true;
    }
    return false;
  }

  /**
   * returns the user_id based on if the user exists.
   *
   * @return int or false.
   */
  public static function emailExists($email, $isActive = true)
  {
    $query =  "SELECT id FROM user WHERE email = ?";
    $connection = Doctrine::getConnectionByTableName("User");
    $results = $connection->execute($query, array($email) );
    $result = $results->fetchAll();
    if(is_array($result) && count($result) > 0)
    {
      return $result[0]['id'];
    }
    return false;
  }

  /**
   * returns user from username
   * by default privacy settings are respected
   *
   * @param   string    $username
   * @param   boolean    $keepPrivacy
   * @return  User
   */
  public static function retrieveByUsername($username, $isActive = true)
  {
    $query = Doctrine_Query::create()->from('User')
          ->where('username = ?', $username)
          ->addWhere('deleted_at is null');

    if( true == $isActive )
    {
      $query->addWhere('status = 1');
    }
    return $query->fetchOne();
  }


  /**
   * returns user from username
   * by default privacy settings are respected
   *
   * @param   string    $username
   * @param   boolean    $keepPrivacy
   * @return  User
   */
  public static function retrieveCollectionByUsername($username, $isActive = true)
  {
    $query = Doctrine_Query::create()->from('User')
          ->where('username = ?', $username)
          ->addWhere('deleted_at is null');

    if( true == $isActive )
    {
      $query->addWhere('status = 1');
    }
    return $query->execute();
  }

  /**
   * Returns a user from apiKey
   *
   * @param string $apiKey
   * @return User
   */
  public static function retrieveByApiKey($apiKey)
  {
    $query = Doctrine_Query::create()
      ->from('User')
      ->where('mclient_salt = ?', $apiKey)
      ->addWhere('deleted_at is null')
      ->addWhere('status = 1');

    return $query->fetchOne();
  }

  /**
   * returns user from username
   * by default privacy settings are respected
   *
   * @param   string    $username
   * @param   boolean    $keepPrivacy
   * @return  User
   */
  public static function retrieveByIds(array $ids)
  {
    $query = Doctrine_Query::create()->from('User')
          ->whereIn('id', $ids);
    return $query->execute();
  }

  /**
   * returns user from username
   * by default privacy settings are respected
   *
   * @param   string        $username
   * @return  User
   */
  public static function retrieveWithPermissions($username, $isActive = true)
  {
    $query = Doctrine_Query::create();
    $query->select('u.*, p.*')
              ->from('User u')
               ->leftJoin('u.Permissions p')
               ->where('u.username = ?', $username)
               ->addWhere('u.deleted_at is null');

    if( true == $isActive )
    {
      $query->addWhere('status = 1');
    }
    return $query->fetchOne();
  }

    /**
     * check if username is available in database, also covering deleted users by default
     *
     * @param   string      $username
     * @param   boolean     $includeDeleted
     * @return  boolean
     */
  public static function isUserInDB($username, $includeDeleted = true)
  {
    $query = Doctrine_Query::create()
                ->select('id')
                ->from('User')
                ->where('username = ?', $username);

    if( false == $includeDeleted )
    {
      $query->addWhere('deleted_at is null');
    }
    return $query->fetchOne();
  }

  /**
   * Returns the Rank of the user sent
   *
   * @param Doctrine_Record $user
   * @return Integer
   */
  public static function retrieveRank( $user )
  {
    $query = new Doctrine_Query();
    $query
      ->select('up.id')
      ->from('UserPoints up')
      ->where("up.points >  ( SELECT points from user_points UserPoints WHERE id = ?  )", $user->id );
    return $query->count();
  }

  /**
   * Returns the user points that the user has recieved.
   * (should be about 12-24 hours behind hopefully)
   *
   * @param Doctrine_Record $user
   * @return Integer
   */
  public static function retrieveUserPoints( $user )
  {
    $query = new Doctrine_Query();
    $query->select('up.*')
      ->from('UserPoints up')
      ->where('up.id = ?', $user->id );
    return $query->fetchOne();
  }

  public static function retrieveUsersRelatedFor( $user_ids )
  {
    $query = Doctrine_Query::create()
      ->select("u.*, up.*, us.*, a.*")
      ->from('User u INDEXBY u.id')
      ->leftJoin("u.Permissions up")
      ->leftJoin("u.UserStats us")
      ->leftJoin("u.Avatar a")
      ->whereIn('u.id', $user_ids );
    return $query->execute(array(), Doctrine::HYDRATE_RECORD);
  }

  /**
   * Returns the user activityStatus
   * @param int user_id
   * @param int $time in seconds since last login
   * @param int $stories of stories the user has to have written
   * @return boolean True if was active (has written >= $stories && was logged in since $time) / false
   */

  public static function retrieveActivityStatus($user_id, $stories=10, $time=0)
  {
    $query = new Doctrine_Query();
    $query->select('u.id')
      ->from('User u')
      ->leftJoin('u.UserStats us')
      ->where('u.id = ?', (int)$user_id)
      ->addWhere('u.last_login > NOW()-?', (int)$time)
      ->addWhere('us.storys_total > ?', (int)$stories);
    return $query->count() > 0;
  }

  /**
   * Retrieves a user by apikey and username
   * @param string Username
   * @param string apikey
   * @return User
   */

  public static function retrieveUserByApiKeyAndUsername($username, $apikey)
  {
    $query = new Doctrine_Query();
    $query->select('*')
      ->from('User u')
      ->where('u.username = ?', $username)
      ->addWhere('u.apikey = ?', $apikey);
    return $query->fetchOne();
  }

  /**
   * Return user by id
   *
   * @param int $id Primary id of the user
   * @return User
   */
  public static function retrieveById($id)
  {
    return Doctrine_Query::create()
      ->from('User')
      ->where('id = ?', array($id))
      ->fetchOne();
  }

    /**
   * Retrieve a single user based on the parent and child id.
   *
   * @param int $parent_id User ID of the parent
   * @param int $child_id User ID of the child
   * @return User The user instance if found
   */
  public static function retrieveOneByParent($parent_id, $child_id)
  {
    $query = Doctrine_Query::create()
      ->select('u.*, m.*')
      ->from('User u')
      ->innerJoin('u.Parents p')
      ->innerJoin('u.ApiObject m')
      ->where('u.id = ? AND p.id = ?', array($child_id, $parent_id))
      ->addWhere('u.deleted_at IS NULL')
      ->addWhere('m.type = ?', 'User');

    return $query->fetchOne();
  }

  /**
   * Return number of users based on the parent ID.
   *
   * @param int $parent_id User ID of the parent
   * @param int $status Optional user status to limit on
   * @return int Number of users in set
   */
  public static function retrieveCountByParent($parent_id, $status = null)
  {
    $query = Doctrine_Query::create()
      ->select('id')
      ->from('User u')
      ->innerJoin('u.Parents p')
      ->innerJoin('u.ApiObject m')
      ->where('p.id = ?', $parent_id)
      ->addWhere('u.deleted_at IS NULL')
      ->addWhere('m.type = ?', 'User');

    if (null !== $status)
    {
      $query->addWhere('u.status = ?', $status);
    }

    return $query->count();
  }

    /**
   * Return a collection of users based on the parent ID.
   *
   * @param int $parent_id User ID of the parent
   * @param int $status Optional user status to limit on
   * @param int $offset Offset in query result
   * @param int $limit Limit result
   * @return Doctrine_Collection Collection of users
   */
  public static function retrieveListByParent($parent_id, $status = null, $offset = null, $limit = null)
  {
    $query = Doctrine_Query::create()
      ->select('u.*, m.*')
      ->from('User u')
      ->innerJoin('u.Parents p')
      ->innerJoin('u.ApiObject m')
      ->where('p.id = ?', $parent_id)
      ->addWhere('u.deleted_at IS NULL')
      ->addWhere('m.type = ?', 'User');

    if (null !== $status)
    {
      $query->addWhere('u.status = ?', $status);
    }

    if (null !== $limit)
    {
      $query->limit($limit);
    }

    if (null !== $offset)
    {
      $query->offset($offset);
    }
  $query->setHydrationMode( Doctrine::HYDRATE_ARRAY );
    return $query->execute();
  }

  /**
   * Returns the users that have credit outstanding.
   *
   * @return Doctrine_Collection
   */
  public static function getCreditUsers()
  {
    $query = Doctrine_Query::create();
    $query->select('u.*')
          ->from('User u, Deal d')
          ->where('d.user_id = u.id AND
                   d.credit > 0 AND
                   d.payed = ?',
                  array(false));
    return $query->execute();
  }
  
  public static function getTopUsersOfToday($limit=20)
  {
  	return Doctrine_Query::create()
  	       ->select("
  	       username,
  	       (SELECT COUNT(*) FROM Story AS s WHERE s.user_id = u.id AND s.created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)) as story_count,
  	       (SELECT COUNT(*) FROM Comment AS c WHERE c.user_id = u.id AND c.created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)) as comment_count")
  	      ->from("User u")
  	      ->innerJoin("Story s")
  	      ->innerJoin("Comments c")
  	      ->where("last_login > DATE_SUB(NOW(), INTERVAL 1 DAY)")
  	      ->limit($limit)
  	      ->execute();
  }
}