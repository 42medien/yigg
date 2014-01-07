<?php

/**
 *
 * @package     yigg
 * @subpackage  file
  */
class FileTable extends Doctrine_Table
{
  /**
   * return instance of current table
   *
   * @return Doctrine_Table
   */
  public static function getTable()
  {
    return Doctrine::getTable('File');
  }

  /**
   * returns the attachements of a story
   * @param int Story_id
   * @return File(s)
   */
  public static function getByStoryId($story_id)
  {
    $query = Doctrine_Query::create()
      ->select('f.*')
      ->from('File f')
      ->innerJoin('f.StoryAttachment sa')
      ->where("sa.story_id = ?", $story_id)
      ->addWhere("sa.vid_preview = 0");
    return $query->execute();
  }
}