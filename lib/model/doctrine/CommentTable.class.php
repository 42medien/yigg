<?php
class CommentTable extends Doctrine_Table
{
  private $max_id;
  
  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable('Comment');
  }
  
  public static function getStoryCommentQuery($story_id)
  {
    return Doctrine::getTable("Comment")
           ->getQueryObject()
           ->select('c.*, ca.*, sc.id')
           ->from('Comment c')
           ->innerJoin("c.Author ca")
           ->innerJoin("c.StoryComment sc")
           ->where('sc.story_id = ?', $story_id)
           ->addWhere("c.is_online != 0")
           ->orderBy("c.created_at ASC");
  }

  public function getCommentsOnStory($story_id, $hydrate = Doctrine::HYDRATE_RECORD)
  {
    return self::getStoryCommentQuery($story_id)->execute(array(),$hydrate);
  }


  public static function getLastCommentsForStory( $story_id, $limit = 2)
  {
    $query = self::getStoryCommentQuery($story_id);
    $query->limit($limit);
    return $query->execute();
  }

  public static function getLatestStoryComments($count = 10)
  {
     $query = new Doctrine_Query();
     $query	->select('sc.id, c.created_at, c.deleted_at, s.*')
           ->from('StoryComment sc')
           ->leftjoin('sc.Comment c')
           ->leftJoin('sc.Story s')
           ->where('c.created_at > DATE_SUB( NOW(), INTERVAL 10 DAY)')
           ->addWhere('s.')
           ->groupBy('sc.story_id')
           ->limit($count)
           ->orderBy('c.created_at DESC');
     return $query->execute();
  }

  /**
   * count all comments for story
   * by default ignores deleted
   *
   * @param   Story      $story
   * @param   boolean     $withDeleted
   * @return  integer
   */
  public static function getCount( $story )
  {
    $query = Doctrine_Query::create()
        ->select('id')
        ->from('StoryComment sc')
        ->where('sc.story_id = ?', $story->id);
    return $query->count();
  }

  public static function getLatestComments($limit = 5)
  {
     return Doctrine_Query::create()
           ->select('c.*, sc.*, ca.*, caa.*')
           ->from('Comment c')
           ->innerjoin('c.Stories sc')
           ->leftJoin('c.Author ca')
           ->leftJoin('ca.Avatar caa')
           ->where('c.created_at > DATE_SUB(NOW(), INTERVAL 10 DAY)')
           ->addWhere("c.user_id != 1")
           ->limit($limit)
           ->orderBy('c.created_at DESC')
           ->execute();
  }
}