<?php

/**
 *
 * @package     yigg
 * @subpackage  story
  */
class StoryTagTable extends Doctrine_Table
{

  public static function findByStory( $story )
  {
    $query = Doctrine_Query::create();
    $query->select('*')
          ->from('StoryTag INDEXBY StoryTag.tag_id')
          ->where('story_id = ?', $story['id'] );
    return $query->execute();
  }
}