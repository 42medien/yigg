<?php
class TweetTable extends Doctrine_Table
{
  public static function getTable()
  {
    return Doctrine::getTable('Tweet');
  }
  
  public static function tweetExists($twitter_id)
  {
  	$query = new Doctrine_Query();
	$query ->select("id")
	       ->from("Tweet")
		   ->where("twitter_id = ?", $twitter_id);
    return false !== $query->fetchOne();
  }
}