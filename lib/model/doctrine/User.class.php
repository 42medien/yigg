<?php

/**
 *
 * @package     yigg
 * @subpackage  user
  */
class User extends BaseUser
{
  // available security credentials
  const ADMIN = 1;
  const MODERATOR = 2;
  const POWERUSER = 3;
  const SEO = 4;

  private $cache;

  public function construct()
  {
    $this->cache = new sfParameterHolder();
  }

  /**
   * Checks if this user is permited to post stories
   * @return void
   */
  public function hasPostingStoryPermissions()
  {
    $stats = $this->UserStats;
    if($stats->storys_total >= 1)
    {
      return true;
    }

    return true;
    //return $stats->yiggs_total >= sfConfig::get("app_user_minYiggs", 100)
           //&& $stats->friends_total >= sfConfig::get("app_user_minFriends", 3);
  }
  
  public function isPostStoryBlocked($user_id)
  {
      $user = Doctrine_Query::create()
              ->select('block_post')
              ->from("User")
              ->where("id = ?", $user_id)
              ->fetchArray();
      
      return ($user[0]['block_post'] == 1) ? true : false;
  }

  public function hasAvatar()
  {
    return 0 !== strlen($this->avatar_id);
  }

  public function isSponsor()
  {
    if(is_null($this->cache->get("isSponsor", null)))
    {
      $count = Doctrine_Query::create()
         ->from("Deal")
         ->where("user_id = ?", $this->id)
         ->addWhere("payed = 1")
         ->count();
      $this->cache->set("isSponsor", $count > 0);
    }
    return $this->cache->get("isSponsor");
  }

  public function preInsert($event)
  {
    $this->mclient_salt = yiggTools::createMclientSalt($this->username);

    if(empty($this->salt))
    {
      $this->setSaltAndPassword();
    }

    $this->status = false;

    $stats = new UserStats();
    $this->UserStats = $stats;

    $key = new AuthUserKey();
    $this->AuthUserKey->add($key);
  }

  public function setSaltAndPassword()
  {
    if(!$this->username || !$this->email || !$this->password)
    {
      throw new Exception('setpassword needs a username, password and an email before saving.' . $this->id, 1);
    }

    // before saving the password, we make a salt, and save that sepeartely.
    $this->_set('salt',     sha1( rand(100000,999999)  . $this->username . $this->email));
    $this->_set('password', sha1( $this->salt . $this->password ));
  }


 /**
   * Notifies the user of a new entity being created.
   *
   * @param Object the context object to raise a notification for.
   * @param String the event you're notifing of.
   * @param Boolean Send a notification via standard web (default true)
   * @param Doctrine_Connection the current connection to use
   */
  public function notify($object, $event, $webNotification = true, $conn = false)
  {
    // Throw an exception if we don't have what we need.
    if(null === get_class($object))
    {
      sfContext::getInstance()->getLogger()->log("ERROR: Notification object was null for event {$event}",sfLogger::ERR);
      throw new Exception("Object cannot be null for notification");
    }

    if(true === $webNotification)
    {
      $base = array(
        "recipient_id" => $this->id,
        "ref_object_type" => get_class($object),
        "ref_object_id" => $object->id,
        "type" => strpos("AtMention", $event ) > 0 ? "web_at": "web"
      );

      $notification = $this->notifyVia($base, $conn);
    }

    if( true === $this->wantsNotificationsVia( $event . "Email") && false === empty($this->email))
    {
      try
      {
        $template = "_notification" . get_class($object);
        $partial = new sfPartialView( sfContext::getInstance(), 'atPage', $template, '');

        if(!isset($notification))
        {
          $notification =  new NotificationMessage();
          $notification->created_at = yiggTools::getDbDate();
        }

        $partial->setPartialVars(
          array(
            "email"=> true,
            "version" => "email",
            "obj" => $object,
            "notification" => $notification
          )
        );

        // send notify mail to user
        $email = sfContext::getInstance()->getMailer()->sendEmail(
          $this->email,
          "Du hast eine neue Mitteilung auf YiGG bekommen.",
          $partial->render()
        );
      }
      catch(Exception $e)
      {
        sfContext::getInstance()->getLogger()->log("User::Notify - could not email due to problem " . $e->getMessage(), sfLogger::ERR);
      }
    }
  }

  /**
   * Checks the user settings holder if the value is present.
   * @param type string the channel you wish to check.
   */
  public function wantsNotificationsVia($type)
  {
    return (true === $this->getConfig()->get($type, false, "settings/notification"));
  }

  /**
   * creates a notification message for this user based on the data provided.
   * @data array
   * @conn Doctrine_Connection the current connection to use
   */
  private function notifyVia($data, $conn)
  {
    $notification = new NotificationMessage();
    $notification->fromArray($data);

    $result = $notification->trySave($conn);
    if(true !== $result)
    {
      sfContext::getInstance()->getLogger()->log("Notification save failed for user #" .  $this->id ."\n". strip_tags( ob_get_clean() ) , 2);
    }
    return $notification;
  }

  /**
   * removes the old avatar from the db && filesystem (cleanup)
   */
  public function createAvatar( $avatar = null )
  {
    if( null !== $this->reference('Avatar') && false === empty($this->Avatar->file_name))
    {
      $directory  = $this->Avatar->file_directory . $this->Avatar->file_name . "/";
      if(is_dir( $directory ))
      {
        yiggFileTools::rmdir( $directory );
      }

      // Delete the references first (referencial integrity breaks otherwise)
      $this->save();

      // Delete the avatar.
      $this->Avatar->delete();
    }
    if( isset($avatar) )
    {
      $this->avatar_id = $avatar;
      $this->save();
    }
  }

  /**
   * check given password
   *
   * @param   string  $password
   * @return  boolean
   */
  public function checkPassword( $password )
  {
    return sha1($this->salt . $password) === $this->password;
  }


 /**
   * returns if the current user is an administrator or not.
   *
   * @return Boolean
   */
  public function isAdmin()
  {
    if(is_null($this->cache->get("isAdmin", null)))
    {
      $this->cache->set("isAdmin", $this->hasPermission( self::ADMIN ));
    }
    return $this->cache->get("isAdmin");
  }

  /**
   * checks this user for the permssion levels provided as constants
   * which should be the same as those on the sfUser.
   *
   * @param int $level
   * @return Boolean
   */
  public function hasPermission( $level )
  {
    $permissions = $this->Permissions->toArray();
    if($permissions)
    {
      foreach($permissions as $permission)
      {
        if( $permission['permission_level'] == $level )
        {
          return true;
        }
      }
    }
    return false;
  }

  /**
   * checks to see if all the billing details for this user have been filled out.
   *
   * @return unknown
   */
  public function hasBillingDetails()
  {
    if($this->first_name === null && $this->last_name === null && $this->street === null && $this->zip === null && $this->city === null)
    {
      return false;
    }
    return true;
  }

  /**
   * Returns the current rank of the user in the community.
   *
   * @return Integer
   */
  public function getCommunityRank()
  {
    return UserTable::retrieveRank( $this );
  }

  /**
   * Returns the current users points from the statistics table
   *
   * @return integer
   */
  public function getUserPoints()
  {
    return UserTable::retrieveUserPoints( $this );
  }

  /**
   * Check if $this follows $user
   * @param User $user
   * @return boolean
   */
  public function follows( $user )
  {
    if( $user['id'] === $this->id )
    {
      return false;
    }

    if(false === $this->cache->has('following'))
    {
        $following = Doctrine_Query::create()
           ->select('following_id')
           ->from('UserFollowing')
           ->where('user_id = ?', array($this->id))
           ->fetchArray();

        $this->cache->set('following', $following);
    }

    foreach($this->cache->get('following', array()) as $follower)
    {
        if($follower["following_id"] === $user['id'])
        {
            return true;
        }
    }
    return false;
  }

  /**
   * Get Story count of user
   */
  public function getStoryCount()
  {
  	if (is_null($this->cache->get("storyCount", null)))
    {
      $this->cache->set("storyCount", Doctrine_Query::create()
           ->from("Story")
           ->where("user_id = ?", $this->id)
           ->count());
    }
    return $this->cache->get("storyCount");
  }

  /**
   * Returns wether a user has never done something on yigg or is inactive since a long time
   * @return boolean Active true/false
   */
  public function isActive ()
  {
    if (is_null($this->cache->get("isActive", null)))
    {
      $inactive_time = 30 * 24 * 3600; // FIXME Make this configurable
      $this->cache->set("isActive", UserTable::retrieveActivityStatus($this->id, 10, $inactive_time));
    }
    return $this->cache->get("isActive");
  }

  /**
   * Return the age of the member in years
   * return Mixed Age in Years
   */
  public function getAge()
  {
    if($this->getConfig()->get("birthday", "0000-00-00 00:00:00", "profile") === "0000-00-00 00:00:00")
    {
      return false;
    }

    $birthday = date_parse($this->getConfig()->get("birthday", null ,"profile"));
    $now = date_parse(date("Y-m-d H:i:s", time()));

    $year_diff =  $now["year"] - $birthday["year"];
    $month_diff =  $now["month"] - $birthday["month"];

    if($month_diff === 0)
    {
      $day_diff = $now["day"] - $birthday["day"];
      if($day_diff >= 0)
      {
        return $year_diff;
      }
      return $year_diff - 1;
    }

    if($month_diff < 0)
    {
      return $year_diff - 1;
    }
    return $year_diff;
  }

  public function getPmCount()
  {
    $count = $this->getTable()->findByDql("
       SELECT COUNT(id) AS pm_count from NotificationMessage AS nm
       WHERE nm.recipient_id = ? AND
       read_at IS NULL AND status = 'new' AND
       type = 'web' AND ref_object_type = 'NotificationMessage'",
       array( $this->id ),
       Doctrine::HYDRATE_ARRAY
    );
    $this->cache->set("pmCount", intval($count[0]["pm_count"]));
    return $this->cache->get("pmCount");
  }

  public function getNotificationCount()
  {
    $this->populateMessageCountCacheOnce();
    return $this->cache->get("notificationCount");
  }

  /**
   * Populates the counts for pm and messages to the cache once
   */
  private function populateMessageCountCacheOnce()
  {
    if(true === $this->cache->has("notificationCount") && true === $this->cache->has("notificationCount"))
    {
      return;
    }

    $count = $this->getTable()->findByDql("
       SELECT COUNT(id) AS not_count FROM NotificationMessage AS nm
       WHERE nm.recipient_id = ? AND
       read_at IS NULL AND status = 'new' AND
       type = 'web'",
       array( $this->id ),
       Doctrine::HYDRATE_ARRAY
    );

    $this->cache->set("notificationCount", intval($count[0]["not_count"]));
  }

  public function __toString()
  {
    return $this->username;
  }

  public function getPrimaryKey()
  {
    return $this->id;
  }

  public function followsTag(Tag $tag)
  {
    return Doctrine_Query::create()
           ->from("UserTag")
           ->where("user_id = ?", $this->id)
           ->addWhere("tag_id = ?", $tag->id)
           ->count() > 0;
  }
  
  public function followsDomain(Domain $domain)
  {
    return Doctrine_Query::create()
           ->from("UserDomainSubscription")
           ->where("user_id = ?", $this->id)
           ->addWhere("domain_id = ?", $domain->id)
           ->count() > 0;
  }

  public function getDirectNotificationCount()
  {
    if(false ===$this->cache->get("directNotificationCount", false))
    {
      $count = $this->getTable()->findByDql("
       SELECT COUNT(id) AS directNotificationCount FROM NotificationMessage AS nm
       WHERE nm.recipient_id = ? AND
       read_at IS NULL AND status = 'new' AND
       type = 'web'",
       array( $this->id ),
       Doctrine::HYDRATE_ARRAY
      );
      $this->cache->set("directNotificationCount", $count[0]['directNotificationCount'] );
    }
    return $this->cache->get("directNotificationCount");
  }

  /**
   * Deletes related records if this user is deleted
   */

  public function preDelete($event)
  {
    if($this->id === 1)  // Prevents deletion of Marvin
    {
      sfContext::getInstance()->getLogger()->log("Something wanted to delete Marvin", 1);
      exit();
    }

    $this->Stories->delete();
    $this->NotificationMessage->delete();
    $this->Comment->delete();

    $this->status = 0;
    parent::preDelete($event);
  }
  
  /**
   * Suspends user
   */
  public function suspend()
  {
    if($this->id === 1)  // Prevents suspending of Marvin
    {
      sfContext::getInstance()->getLogger()->log("Something wanted to suspend Marvin", 1);
      exit();
    }

    $this->block_post = 1;
    $this->save();
  }
}