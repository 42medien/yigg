<?php

/**
 * StoryFinder provides all functionality around finding and sorting stories by several
 * criterias
 *
 * Example returns all stories with tag 'news' as hydrated objects
 * $stories = new yiggStoryFinder();
 * $stories->confineWithTag('news')->execute();
 *
 */
class yiggStoryFinder
{
  const HYDRATE_ARRAY     = Doctrine::HYDRATE_ARRAY;
  const HYDRATE_OBJECT    = Doctrine::HYDRATE_RECORD;

  const SORT_DESC         = 'DESC';
  const SORT_ASC          = 'ASC';

  // use our rating algorithim.
  private $use_algorithim = false;
  
  // use new news algo
  private $use_news_algorithim = false;
  
  /**
   * sql statement to find a story
   *
   * @var    Doctrine_RawSql
   */
  private $query;

  /**
   * sorting
   * @var array
   */
  private $selectors    = array();
  private $joins        = array();
  private $wheres       = array();
  private $sorters      = array();
  private $callbacks    = array();

  /**
   * by default, completly hydrated objects are returned from fetchResult
   * @var    integer
   */
  private $hydrationMode = self::HYDRATE_OBJECT;

  /**
   * @var    boolean
   */
  private $deleted      = false;

  /**
   * @var array
   */
  private $resultIds    = array();

  /**
   * @var boolean
   */
  private $executed     = false;

  /**
   * @var Doctrine_Resultset
   */
  private $hydrationCache;

  /**
   * @var daterange for storyfinder
   */
  private $time_until = null;
  private $time_from = null;
    
  /**
   * @var precompiled sql
   */
  private $sql = '';

  /**
   * create a new story finder
   *
   * @return StoryFinder
   */
  public function __construct()
  {
      $this->query = new Doctrine_RawSql();
      $this->hydrateArray();
      return $this;
  }

  public static function create()
  {
    return new self;
  }



  /**
   * only story since the given $date and $time
   * $time is by default midnight
   *
   * @param string $date
   * @param string $time
   * @return StoryFinder
   */
  public function confineWithDateFrom($date, $time = '00:00:00')
  {
      $this->time_from = $this->prepareDate($date, $time);
      return $this;
  }

  /**
   * only story until the given $date and $time
   * $time is by default the last second of the day (23.59.59)
   *
   * @param string $date
   * @param string $time
   * @return StoryFinder
   */
  public function confineWithDateUntil($date, $time = '23:59:59')
  {
      $this->time_until = $this->prepareDate($date, $time);
      return $this;
  }

  /**
   * only story since today
   *
   * @return StoryFinder
   */
  public function confineWithDateToday()
  {
      $today = date('Y-m-d');
      $this->confineWithDateFrom($today);
      return $this;
  }

  /**
   * only story since today
   *
   * @return StoryFinder
   */
  public function confineWithDate24($round=false)
  {
    // get last 24 hours timeframe
    $context = time();
    if( true === $round )
    {
      $this->time_from = yiggTools::getRoundedTime( $context - 86400 );
      $this->time_until = yiggTools::getRoundedTime( $context );
    }
    else
    {
      $this->time_from = yiggTools::getDbDate( null, $context - 86400);
      $this->time_until = 'NOW()';
    }
    return $this;
  }

  /**
   * Only stories that are videos
   * @return StoryFinder
   */
   public function confineWithVideo()
   {
       $this->wheres['video'] = "s.type = 2";
       return $this;
   }

  /**
   * only story since yesterday
   *
   * @return StoryFinder
   */
  public function confineWithDateYesterday()
  {
    $context = time();
    $this->time_from = yiggTools::getRoundedTime( $context - (86400 * 2) );
    $this->time_until = yiggTools::getRoundedTime( $context - 86400  );
    return $this;
  }

  /**
   * only story since last week
   *
   * @return StoryFinder
   */
  public function confineWithDateLastWeek()
  {
      $lastWeek = date("Y-m-d", mktime(0,0,0,date("m"), date("d")-7, date("Y")));
      $this->confineWithDateFrom($lastWeek);
      return $this;
  }

    /**
     * confines search with category
     *
     * @param category_id
     * @return StoryFinder
     */
    public function confineWithCategory($category_id)
    {
        $this->joins['category'] = 'INNER JOIN story_category on s.id = story_category.story_id';
        $this->wheres['story_category.category_id'] = sprintf("%s = %s", "story_category.category_id", $category_id);
        return $this;
    }

  /**
   * create criteria category, accepts id or category internal_url as value
   *
   * @param integer|string    $criteria
   * @return StoryFinder
   */
  public function confineWithCategoryId($category_id)
  {
    $this->wheres['category'] = 's.category_id = ' . $category_id;
    return $this;
  }

  /**
   * create criteria for confining with a sub-category id
   */
  public function confineWithSubCategoryId($subcategory_id)
  {
    $this->wheres['subcategory'] = 's.subcategory_id = ' . $subcategory_id;
    return $this;
  }

  /**
   * confines by just the URL of the category.
   *
   * @param unknown_type $category_url
   * @return unknown
   */
  public function confineWithCategoryUrl( $category_url )
  {
    $this->wheres['category'] = 'category.internal_url LIKE \'' . $category_url . '\'';
    $this->joins['category'] = 'INNER JOIN category on s.category_id = category.id';
    return $this;
  }

  public function confineWithMarkedForFrontpage($user_id=null)
  {
      $this->joins['history'] = 'INNER JOIN history on s.id = history.story_id';

      if(0 !== strlen($user_id))
      {
        $this->wheres['history'] = "(history.user_id IS NULL OR history.user_id = '$user_id')";
      }
      else
      {
        $this->wheres['history'] = "history.user_id IS NULL";
      }
      return $this;
  }

  /**
   * confines a search with Users
   * @param $users
   * @return StoryFinder
   */
  public function confineWithUsers( $users )
  {
    return $this->addWhereByArray("s.user_id", $this->getCriteriaIds($users));
  }

  /**
   * Filter a specific users news
   * @param int $user_id
   * @return StoryFinder
   */

  public function filterByUserId( $user_id )
  {
    $this->wheres['filter_userid'] = 's.user_id != '.$user_id;
  }


  /**
   * confines search with a doctrine collection of tag(s)
   *
   * @param Doctrine_Collection $criteria indexed by tag_id
   * @return StoryFinder
   */
  public function confineWithTags($tags)
  {
    $this->joins['tag'] = 'INNER JOIN story_tag on s.id = story_tag.story_id';
    return $this->addWhereByArray("story_tag.tag_id", $this->getCriteriaIds($tags));
  }

  /**
   * create criteria with id, accepts (int) id or internal story url
   *
   * @param    string            $criteria
   * @return    StoryFinder
   */
  public function confineWithId($criteria)
  {
    if( $id = intval($criteria) )
    {
      $this->query->addWhere('s.id = ?', $id);
    }
    else
    {
      $this->query->addWhere('s.internal_url = ?', $criteria);
    }
    return $this;
  }

    /**
     *
     * @param    string  $criteria
     * @return    StoryFinder
     */
    public function excludeId($criteria)
    {
        if( $id = intval($criteria) )
        {
            $this->wheres['id'] = 's.id != '.$id;
        }
        return $this;
    }

  public function confineWithStoriesWithComments()
  {
    $this->joins['story_comments'] = 'INNER JOIN story_comment on s.id = story_comment.story_id';
    return $this;
  }
              
  /**
   * confine resultset by average votes
   * the voting average is calculated, stories with less than the average votes will
   * not be selected
   *
   * @return StoryFinder
   */
  public function sortByAvg()
  {
    $this->use_algorithim = true;
    $this->selectors['avg'] = '
    (
     SELECT count(1)
     FROM story_render v
     WHERE v.story_id = s.id AND
      s.created_at > \'' . $this->time_from .'\'
         AND
        s.created_at < \''. $this->time_until .'\'
     ) AS views,
    (
     SELECT count(c.id)
     FROM comment c
     INNER JOIN story_comment scom on c.id = scom.comment_id
     WHERE scom.story_id = s.id AND c.deleted_at is null AND
      c.created_at > \'' . $this->time_from . '\'
        AND
      c.created_at < \''. $this->time_until .'\'
     ) AS comments,
    (
      SELECT count(r.id)
      FROM story_rating r
      LEFT JOIN rating ON r.rating_id = rating.id
      WHERE r.story_id = s.id
        AND
      rating.created_at > \'' . $this->time_from . '\'
        AND
      rating.created_at < \''. $this->time_until .'\'
    ) AS votes,
    (
      SELECT count(st.id)
      FROM story_tweet st
      WHERE st.story_id = s.id
    ) AS story_tweet,
    (
      SELECT count(ur.id)
      FROM story_rating ur
      LEFT JOIN rating ON ur.rating_id = rating.id
      WHERE
        ur.story_id = s.id
      AND
        ur.user_id != 1
      AND
      rating.created_at > \'' . $this->time_from . '\'
        AND
      rating.created_at < \''. $this->time_until .'\'
    ) as user_votes
    ';

    $this->sorters['avg'] = "s.points DESC";
    return $this;
  }

  /**
   * sort result by rating, using subquery to calculate all ratings
   *
   * @return
   * @param $direction Object[optional]
   */
  public function sortByRating($direction = self::SORT_DESC)
  {
    $this->selectors['rating'] = '
    (
      SELECT
        COUNT(sr.id)
      FROM
        story_rating sr
      WHERE
        sr.story_id = s.id
    ) as rating';
    $this->sorters['rating']    = 'rating ' . $direction;
    return $this;
  }

  /**
   * sort result by comments rating
   *
   * @return
   * @param $direction Object[optional]
   */
  public function sortByCommentNumber($direction = self::SORT_DESC)
  {
    // count non-deleted comments as sorting criteria
    $this->selectors['comments'] = '(
      SELECT
        COUNT(c_count.id)
      FROM
        comment c_count INNER JOIN
        story_comment ON story_comment.comment_id = c_count.id
      WHERE
        story_comment.story_id = s.id
      AND
        c_count.deleted_at IS NULL
      ) AS c_count
    ';
    $this->sorters['comments'] = 'c_count ' . $direction;
    return $this;
  }

  /**
   * filter for storys where a certain user has voted
   *
   * @param string direction
   * @return StoryFinder
   */
  public function confineWithUserHasVoted( $user_id )
  {
    $this->joins['user_voted'] = 'INNER JOIN story_rating sr on s.id = sr.story_id';
    $this->wheres['user_voted'] = 'sr.user_id = ' . $user_id;
    return $this;
  }

  /**
   * filter for storys that a certain user has commented
   *
   * @param string direction
   * @return StoryFinder
   */
  public function confineWithUserHasCommented($user_id)
  {
    $this->joins['comment'] = 'INNER JOIN story_comment on s.id = story_comment.story_id INNER JOIN comment on story_comment.comment_id = comment.id';
    $this->wheres['comment'] = 'comment.user_id = ' . $user_id;
    return $this;
  }

  /**
   * filter for storys that a certain user has vieved
   *
   * @param string direction
   * @return StoryFinder
   */
  public function confineWithUserHasViewed($user_id)
  {
    $this->joins['user_viewed'] = 'INNER JOIN story_render sre on s.id = sre.story_id';
    $this->wheres['user_viewed'] = 'sre.user_id = ' . $user_id;
    return $this;
  }

  /**
   * sort stories by date of latest comments
   *
   * @param     string $direction
   * @return     StoryFinder
   */
  public function sortByLatestComment($direction = self::SORT_DESC)
  {
    // count non-deleted comments as sorting criteria
     $this->selectors['latestComment'] = '
      (
      SELECT
        sc.created_at
      FROM
        story_comment sc INNER JOIN comment as c_sort on sc.comment_id = c_sort.id
      WHERE
        sc.story_id = s.id
       AND
        c_sort.deleted_at IS NULL
      ORDER BY sc.created_at
      LIMIT 1
      )
      AS latestComment
    ';

    $this->sorters['latestComment']    = 'latestComment '  . $direction;
    return $this;
  }

  /**
   * sort stories by the date of theyre latest rating
   * @param string direction
   * @return StoryFinder
   */
  public function sortByRatingDate($direction = self::SORT_DESC)
  {
    $this->selectors['latestRating'] = '(
      SELECT
        (r.created_at)
      FROM
        StoryRating sr
      LEFT JOIN
        Rating r
      ON
        sr.rating_id = r.id
      WHERE
        sr.story_id = s.id
      ORDER BY
        (r.created at)
      LIMIT(1)
      ) as latestRating';

    $this->sorters['latestRating']    = 'latestRating '. $direction;
    return $this;
  }

  /**
   * sort stories by popularity (single views)
   *
   * @param     string $direction
   * @return     StoryFinder
   */
  public function sortByViews($direction = self::SORT_DESC)
  {
    // count non-deleted comments as sorting criteria
    $this->selectors['views'] = '
    (
     SELECT count(*)
     FROM story_render as v
     WHERE v.story_id = s.id
       AND
       s.created_at > \'' . $this->time_from .'\'
        AND
       s.created_at < \''. $this->time_until .'\'
     ) AS views
    ';
    $this->sorters['views']    = 'views '  . $direction;
    return $this;
  }

  /**
   * sort stories by the creation date
   *
   * @param unknown_type $direction
   * @return unknown
   */
  public function sortByDate($direction = self::SORT_DESC)
  {
    $this->sorters['date']    = 's.created_at '  . $direction;
    return $this;
  }

  /**
   * add a callback function which is invoked on every element in resultlist
   *
   * @param   string  $name   callback name
   */
  public function addCallback($name)
  {
    $id = strtolower($name);
    $this->callbacks[$id] = $name;
  }

  /**
   * remove a named callback if set
   *
   * @param   string  $name   callback name
   */
  public function removeCallback($name)
  {
    $id = strtolower($name);
    if( false === isset($this->callbacks[$id]) )
    {
      return;
    }
    unset($this->callbacks[$id]);
  }

    /**
     * set limit for query
     *
     * @param     integer        $limit
     * @param     integer        $pager
     * @return yiggStoryFinder
     */
  public function setLimit($limit = 0, $pager = 0)
  {
    $this->query->limit($limit, $pager);
    return $this;
  }

  /**
   * set resultset to be hydrated as objects
   *
   * @return  yiggStoryFinder
   */
  public function hydrateArray()
  {
    $this->hydrationMode = self::HYDRATE_ARRAY;
    return $this;
  }

  /**
   * set resultset to be hydrated as objects
   *
   * @return  yiggStoryFinder
   */
  public function hydrateObject()
  {
    $this->hydrationMode = self::HYDRATE_OBJECT;
    return $this;
  }

  /**
   * return only the number of stories matching the criteria
   * @return yiggStoryFinder
   */
  public function getCount()
  {
    return count($this->hydrationCache);
  }

  /**
   * just return the resultset ids
   *
   * @return
   */
  public function getRawIds()
  {
    return $this->resultIds;
  }

  /**
   * method is called after building the sql criteria but before the execution
   *
   * @return void
   */
  public function preExecute()
  {}

  /**
   * execute Storyfinder and calculates the stories matching the provided criteria
   * does not hydrate any object so far, just prepare statement and execute
   *
   * to get a result from the execution, use getResult() / getArray()
   *
   * @return StoryFinder
   */
  public function execute()
  {
     // prepare query for execution
    $this->prepareQuery();

    // execute and save for later getResult()
    $this->hydrationCache = $this->query->execute(array(), $this->hydrationMode);

    // process callbacks on every single result
    $this->processCallbacks();

    return $this;
  }

  public function executeQuery()
  {
    return $this->getQuery()->execute();
  }

  /**
   * Confines a search with an array
   * @param array_of ids
   * @return StoryFinder
   */

  private function addWhereByArray($field, $values)
  {
    if( count($values) > 1 )
    {
      $this->wheres[$field] = sprintf("%s IN (%s)", $field, implode(',', $values));
      return $this;
    }

    $this->wheres[$field] = sprintf("%s = %s", $field, array_pop($values));
    return $this;
  }

  /**
   * Extracts ids from records (can handle Hydrate_Array and Hydrate_Record)
   */
  private function getCriteriaIds( $criteria )
  {
    switch(get_class($criteria))
    {
      case false:
        throw new Exception("unknown criteria type");
        break;
      case "Doctrine_Collection":
        $criteria = $criteria->toArray();
        break;
      default:
        $criteria = array($criteria); // Prepare a single record
        break;
    }
    return array_map(create_function('$a','return $a["id"];'), $criteria);
  }

  /**
   * Adds selectors to the sql query for this instances needs.
   *
   */
  public function processSelectors()
  {
    $selectors  = count($this->selectors) > 0 ? ",\n" . implode(',', $this->selectors) : '' ;
    if( count($this->selectors) )
    {
      $this->sql =  '
      (
         SELECT
           s.* '. $selectors. '
           FROM ' . $this->sql .
      ') as ' . (($this->use_algorithim == true || $this->use_news_algorithim == true) ? 'sv' : 's');
    }
  }

  /**
   * Adds the joins to the SQL that are needed for the isntance of storyfinder.
   *
   */
  public function processJoins()
  {
    //@todo this isn't optimised at all!
    $this->joins['user'] = 'LEFT JOIN user AS u ON s.user_id = u.id';
    $this->sql .= implode("\n", $this->joins);
  }

  /**
   * Processes the where clauses for the storyfinder.
   *
   */
  public function processWheres()
  {
    $this->sql .= '
      WHERE ';

    if($this->time_from)
    {
      $this->sql .= '
        s.created_at > \'' . $this->time_from . '\' AND
      ';
    }

    if($this->time_until)
    {
      $this->sql .= '
        s.created_at < \''.  $this->time_until .'\' AND
     ';
    }

    $this->sql .= count($this->wheres) > 0 ? implode(' AND ', $this->wheres) . ' AND ' : '';

    $this->sql .= '
      s.deleted_at IS NULL
       AND
      u.deleted_at IS NULL
    ';
  }
  
  /*
   * new Algorythm for calculation the news on the main page of yigg 
   * FORMULA:
   * 1 * counts of yiggs + 
   * 1 * counts of Tweets (last 24 hours) + 
   * 2 * counts of Tweets (after the first 24 hours) + 
   * 1 * counts comments 
   * /--  1,5 * shares via spreadly --/ - excluded from the formula calculation
   * 
   * @return
   * @param $direction Object[optional]
   */
  public function sortByYTTCS($direction = self::SORT_DESC)
  {
    $this->use_news_algorithim = true;
    
    $context = time();
    $this->time_until = yiggTools::getRoundedTime( $context - 86400 ); // yesterday    
    $this->time_from = yiggTools::getRoundedTime( $context ); // time at the moment    
                
    $this->selectors['yttcs'] = '    
     (
     SELECT count(c.id)
     FROM comment c
     INNER JOIN story_comment scom on c.id = scom.comment_id
     WHERE scom.story_id = s.id AND c.deleted_at is null
     ) AS comments,
    (
      SELECT count(r.id)
      FROM story_rating r
      LEFT JOIN rating ON r.rating_id = rating.id
      WHERE r.story_id = s.id
        AND
      rating.created_at > \'' . $this->time_until . '\'
        AND
      rating.created_at < \''. $this->time_from .'\'
    ) AS votes,
    (
      SELECT count(st.id)
      FROM story_tweet st
      LEFT JOIN tweet AS t ON t.id = st.tweet_id
      WHERE st.story_id = s.id 
        AND
            t.created_at > \'' . $this->time_until .'\'
        AND
            t.created_at < \''. $this->time_from .'\'
    ) AS story_tweet_l,
    (
      SELECT count(st.id)
      FROM story_tweet st
      LEFT JOIN tweet AS t ON t.id = st.tweet_id
      WHERE st.story_id = s.id 
        AND t.created_at < \'' . $this->time_until .'\'
    ) AS story_tweet_a
    ';
        
    $this->time_from = null;
    $this->time_until = null;

    $this->sorters['yttcs'] = "s.yttcs DESC";
    return $this;
  }

  /**
   * prepare query with sorters, selectors and joins
   *
   */
  private function prepareQuery()
  {
    $this->query->select('{s.*}');
    $this->sql = '
      story as s
    ';
    $this->processJoins();

    $this->processWheres();

    $this->processSelectors();

    if($this->use_algorithim == true)
    {
      $this->sql = '
        (
        SELECT
          ROUND(
            (
             ( avg( sv.user_votes +  (sv.story_tweet) + (sv.votes / 10)) * avg(sv.views) )
              +
               (
                (
                 ( sv.user_votes + (sv.votes / 10) / avg(sv.user_votes) ) * 2
                )
                *
                (
                  ( (sv.views * 2 ) + 1) / avg(sv.views)
                )
                * 2
               )
               + (sv.comments * 5)
              )
              /
              ( avg(sv.user_votes + (sv.story_tweet) + (sv.votes / 10)) + avg(sv.views) )
            ) as points,
            sv.*
          FROM
         '. $this->sql . '
         GROUP BY
         sv.id
      ) as s
      ';
      $this->query->addWhere('s.user_votes > 2');
    }
    if($this->use_news_algorithim == true)    
    {
        $this->sql = '
        (
        SELECT
          ROUND(
                (1 * sv.votes ) + 
                (1 * sv.story_tweet_l) + 
                (2 * sv.story_tweet_a) + 
                (1 * sv.comments)
            ) as yttcs,
            sv.*
          FROM
         '. $this->sql . '
         GROUP BY
         sv.id
      ) as s
      ';
    }
    
    $this->query->from($this->sql);

    $this->query->addComponent("s","Story");

    // create sorting criterias
    if( count($this->sorters) )
    {
      $this->query->orderBy(implode(',', $this->sorters));
    }


    return $this;
  }

  /**
   * just return query as doctrinie query object as is
   *
   * @return     Doctrine_Query
   */
  public function getQuery()
  {
    $this->prepareQuery();
    return $this->query;
  }

  /**
   * fetch resultset, by default objects are hydrated and returned (see also hydrateArray())
   * resultsets are cached in a hydration c ache by default, so multiply calls to fetchResult
   * will not touch the database, may be forced with $forceHydration
   *
   * @return      integer|array|Doctrine_Resultset
   */
  public function getResult()
  {
    // return if no result was found
    if( false == count($this->hydrationCache) )
    {
      // return hydration mode conform
      if( $this->hydrationMode == self::HYDRATE_ARRAY )
      {
        return array();

      }
      else
      {
        return new Doctrine_Collection();
      }
    }
    return $this->hydrationCache;
  }

  /**
   * calls getResult and returns an array, even if hydration is set to object
   * should only be used e.g. to get an array from already hydrated objects to
   * avoid hydration overhead, use getResult and hydrationMode HYDRATE_ARRAY
   * instead for performance reasons
   *
   * @return      integer|array|Doctrine_Resultset
   */
  public function getArray()
  {
    if( $this->hydrationMode == self::HYDRATE_ARRAY )
    {
      return $this->getResult();
    }
    return $this->getResult()->toArray();
  }

  /**
   * if set, callbacks will be called on every story inside the resultset
   *
   * @return
   */
  public function processCallbacks()
  {
    // no callbacks => no action
    if( true == empty($this->callbacks) )
    {
      return;
    }

    foreach( $this->getResult() as $object )
    {

      // we need an object for callback actions
      if( is_array($object) )
      {
        $object = StoryTable::retrieveById($object['id']);
      }

      foreach($this->callbacks as $callback)
      {
        $object->$callback();
      }
    }
  }

  /**
   * prepare date for database format
   *
   * @param string $date
   * @return string
   */
  private function prepareDate($date, $time)
  {
      $date = date('Y-m-d', strtotime($date));
      return date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
  }
}
