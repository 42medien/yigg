<?php
class SystemStatsTable extends Doctrine_Table
{
  /**
   * Factory for creating a new stat of now
   * @static
   * @return SystemStats
   */
  public static function createNewStats()
  {
    $stats = new SystemStats();
    $stats->fromArray(
      array(
	    "total_user" => Doctrine::getTable("User")->count(),
	    "total_story"   => Doctrine::getTable("Story")->count(),
	    "total_comments" => Doctrine::getTable("Comment")->count(),
        "total_friends" => Doctrine::getTable("UserFollowing")->count(),
	    "total_votes" => Doctrine::getTable("StoryRating")->count(),
	    "total_tags" => Doctrine::getTable("Tag")->count(),
        "calculated" => new Doctrine_Expression("NOW()"))
    );
    $stats->save();
    return $stats;
  }
}
?>