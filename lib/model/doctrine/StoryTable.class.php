<?php
class StoryTable extends Doctrine_Table
{
  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable('Story');
  }

  /**
   * Returns the query that fetches all stories that a user subscribed to
   * @static
   * @param  $user_id
   * @return Doctrine_Query
   */
  public static function getSubscribedStoriesQueryByUserId($user_id)
  {
    return Doctrine_Query::create()
      ->from("Story s")
      ->leftJoin('StoryTag st')
      ->where("st.tag_id IN (SELECT ut.tag_id FROM UserTag ut WHERE ut.user_id = ?)
                OR s.user_id IN (SELECT uf.following_id FROM UserFollowing uf WHERE uf.user_id = ?)
                OR s.domain_id IN (SELECT ud.domain_id FROM UserDomainSubscription ud WHERE ud.user_id = ?)",
              array($user_id, $user_id, $user_id))
      ->addWhere("s.created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK)")
      ->orderBy("s.created_at DESC");
  }

  /**
   * Get number of Stories from today
   * @return Integer
   */

  public static function getTodaysStorysCount()
  {
    return self::getBasicQuery()
           ->where('created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)')
           ->count();
  }

  /**
   * Get a query for all stories from a domain
   * @param Domain $domain
   * @return Doctrine_Query
   */
  public static function getQueryByDomain(Domain $domain)
  {
    return self::getBasicQuery()->where("domain_id = ?", $domain->id);
  }

  public static function getBasicQuery()
  {
     return Doctrine_Query::create()
           ->from("Story")
           ->orderBy("id DESC");
  }

  /**
   * count all stories for $criteria (all if empty)
   * by default ignores deleted
   *
   * @param   Doctrine_Query  $criteria
   * @param   boolean     $withDeleted
   * @return  integer
   */
  public static function getCount( $criteria = null, $withDeleted = false )
  {
    if( true == isset($criteria) )
    {
      $query = clone $criteria;
    }
    else
    {
      $query = Doctrine_Query::create();
    }

    $query
      ->select('COUNT(id) as num_rows')
      ->from('Story s');

    if( false == $withDeleted ){
      $query->addWhere('s.deleted_at IS NULL');
    }

    return (int) $query->fetchOne()->num_rows;
  }

    /**
     * Returns all stories matching a particular tag
     *
     * @param   string        $tag    a tag name
     * @param  Doctrine_Query    $criteria  Criteria Object
     * @return   Doctrine_Collection       a result set of stories matching your request.
     */
  public static function retrieveByTag( $tag, $criteria = null )
    {
    $query = self::buildDqlForStoryWithJoins($criteria)
      ->leftJoin('s.Tags t')
          ->where('t.name like ?', '%' . $tag . '%')
          ->addWhere('s.deleted_at IS NULL');

    return $query->execute();
    }


    /**
     * Returns all stories from a bunch of tag names.
     * Tags are set in the URL as being space seperated list
     *
     * @param  String $tags a space seperated tag list
     * @param  Doctrine_Query $criteria  Criteria Object
     * @return Doctrine_Collection a result set of stories matching your request.
     */
  public static function retrieveByTags( $tags, $offset = null, $limit = null )
  {
    $tags = Tag::parseArray($tags);
    $query = self::buildDqlForStoryWithJoins()
      ->leftJoin('s.Tags t')
      ->whereIn('t.name', $tags)
      ->addWhere('s.deleted_at IS NULL');

    if (null !== $limit)
    {
      $query->limit($limit);
    }

    if (null !== $offset)
    {
      $query->offset($offset);
    }
    return $query->execute();
  }

  public static function retrieveByTagRelation( $tags )
  {
    foreach($tags as $tag )
    {
      $tag_names[] = $tag['name'];
    }
    $query = self::buildDqlForStoryWithJoins()
      ->leftJoin('s.Tags t')
      ->whereIn('t.name', $tag_names)
      ->addWhere('s.deleted_at IS NULL');
    return $query->execute();
  }

  public static function retrieveUserStoryCount($user)
  {
    $query = self::buildDqlForStoryWithJoins();
    $query->addWhere("user_id = ?",$user['id']);
    return self::getCount($query);
  }

  public static function retrieveLatestStories($count = 5)
  {
    $time = time();
    $hours = ($time - 60*60*12);

    return yiggStoryFinder::create()
       ->sortByDate()
       ->confineWithDateFrom(  date('Y-m-d', $hours), date("H:i:s", $hours))
       ->confineWithDateUntil( date('Y-m-d', $time),  date("H:i:s", $time))
       ->setLimit($count)
       ->executeQuery();
  }

  /**
   * Retrieve latest unsubmitted story by id and user_id
   * @param int $user_id ID of the user
   * @param int $story_id Story ID
   * @param int $gracetime Time in seconds that the story may be old
   * @return Story
   */
  public static function retrieveUnfinishedById($story_id, $user_id )
  {
   $query = Doctrine_Query::create();
   $query  -> select('s.*')
       ->from('Story s')
       ->where('s.user_id = ?', (int) $user_id)
       ->addWhere('s.deleted_at IS NULL')
       ->addWhere('s.id = ?', (int)$story_id)
       ->addWhere('s.created_at > DATE_SUB( NOW(), INTERVAL 15 MINUTE )')
       ->orderby('s.id DESC');
   return $query->fetchOne();
  }

  /**
   * Retrieve a list of story for a parent user.
   *
   * @param int $parent_id The ID of the parent user
   * @param int $child_id Only storys for the child with ID
   * @param int $offset Offset in query result
   * @param int $limit Limit result
   * @return Doctrine_Collection Colection of storys
   */
  public static function retrieveListWithOffset($user_id, $offset = null, $limit = null)
  {
    $query = Doctrine_Query::create()
      ->select('s.*, u.*, c.*, t.*, m.*')
      ->from('Story s')
      ->leftJoin('s.Author u')
      ->leftJoin('s.Category c')
      ->leftJoin('s.Tags t')
      ->where('u.id = ?', $user_id)
      ->addWhere('u.deleted_at IS NULL')
      ->addWhere('s.deleted_at IS NULL');

    if (null !== $limit)
    {
      $query->limit($limit);
    }

    if (null !== $offset)
    {
      $query->offset($offset);
    }

    return $query->execute();
  }

  public static function isDeleted($url)
  {
     // check to see if the user was deleted already.
    $query =  "SELECT deleted_at FROM story WHERE external_url = ?";
    $connection = Doctrine::getConnectionByTableName("User");
    $results = $connection->execute($query, array($url) );
    $result = $results->fetchAll();
    if( is_array($result) && count($result) > 0 && (int) strtotime($result[0]['deleted_at']) > 0 )
    {
      return true;
    }
    return false;
  }

  /**
   * Retrieve the count of stories for a parent user.
   *
   * @param int $parent_id The ID of the parent user
   * @param int $child_id Only storys for the child with ID
   * @return int Number of storys
   */
  public static function retrieveCountByParent($parent_id, $child_id = null)
  {
    $query = Doctrine_Query::create()
      ->select('s.id')
      ->from('Story s')
      ->leftJoin('s.Author u')
      ->innerJoin('s.ApiObject m')
      ->innerJoin('u.Parents p')
      ->where('p.id = ?', $parent_id)
      ->addWhere('u.deleted_at IS NULL')
      ->addWhere('s.deleted_at IS NULL')
      ->addWhere('m.type = ?', 'Story');

    if (null !== $child_id)
    {
      $query->addWhere('u.id = ?', $child_id);
    }

    return $query->count();
  }

  /**
   * Retrieve storys by multiple ids
   *
   */
  public static function getStorysByIds($story_ids, $limit=25)
  {
    $query = Doctrine_Query::create()
    ->select('s.*')
    ->from('Story s')
    ->whereIn('s.id',$story_ids)
    ->orderBy('s.id DESC')
  ->limit($limit);
    return $query->execute();
  }

  public static function getLastAdded($limit=5)
  {
    return yiggStoryFinder::create()
    	->sortByDate()
        ->confineWithDate24()
        ->setLimit($limit)
        ->executeQuery();
  }

  /**
   * Get latest stories of all associate users
   * @param $user
   * @return Docrtrine_Collection
   */
  public static function getAsscociateUserStories($user_id)
  {
    $query = new Doctrine_Query();
    $query->select("s.*")
          ->from("Story s")
          ->innerJoin("s.Comments sc")
          ->where("s.user_id IN (SELECT am.object_id FROM ApiObjectMap as am WHERE am.user_id = ? AND am.type = 'User')", $user_id)
          ->addWhere("s.created_at > DATE_SUB(NOW(),INTERVAL 1 WEEK)")
          ->orderBy("sc.created_at DESC");
    return $query->execute();
  }
}