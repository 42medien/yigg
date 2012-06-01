<?php

/**
 * 
 * @package     yigg
 * @subpackage  story
  */
class StoryRenderTable extends Doctrine_Table
{
  public static function trackStoryRender(Story $story, User $user)
  {
    $render_count = Doctrine_Query::create()
    ->from("StoryRender")
    ->where("story_id = ?", $story->id)
    ->addWhere("user_id = ?", $user->id)
    ->count();

    if($render_count === 0)
    {
      $render = new StoryRender();
      $render->story_id = $story->id;
      $render->user_id = $user->id;
      $render->save();
    }
  }
}