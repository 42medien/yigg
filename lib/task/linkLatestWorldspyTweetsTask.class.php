<?php
class linkLatestWorldspyTweets extends yiggTaskToolsTask
{
protected function configure()
  {
    $this->namespace        = 'worldspy';
    $this->name             = 'linkLatestTweets';
    $this->briefDescription = 'Link the latest tweets from the worldspy with stories';
    $this->addArgument('application', sfCommandArgument::REQUIRED, 'The application name');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'prod');
  }

  public function createStoryTweet($tweet, $story)
  {
    // Does this tweet already exist?
    if(false !== Doctrine::getTable("Tweet")->findOneByTwitterId($tweet->twitter_id))
    {
      return;
    }

    $t = new Tweet();
    $t->fromArray(
      array(
        "twitter_id" => $tweet->twitter_id,
        "username" => $tweet->username,
        "profile_image_url" => $tweet->profile_image,
        "created_at" => $tweet->created_at,
        "text" => $tweet->text,
        "source" => $tweet->source)
    );
    $t->trySave();
    $st = new StoryTweet();
    $st->fromArray(
      array(
        "story_id" => $story->id,
        "tweet_id" => $t->id
      )
    );

    $st->trySave();
    $story->initalVote();
}

  /**
   *
   * Setup the task and execute it.
   */
  public function executeWork($arguments = array(), $options = array())
  {
    $tweets = Doctrine_Query::create()
            ->select("*")
            ->from("WspyTweet")
            ->where("id > ?",  $this->status->getConfig()->get("bigest_wspy_tweet_id"))
            ->limit(500)
            ->execute();

    foreach($tweets as $tweet)
    {
      try
      {
        $story = Doctrine::getTable("Story")->findOneByExternalUrl($tweet->link);
        if(false === $story)
        {
          continue;
        }
        $this->createStoryTweet($tweet, $story);
      }
      catch(Exception $e)
      {
        $this->log($e->getMessage());
      }

      if(isset($tweet) && false === empty($tweet->id))
      {
        $this->status->getConfig()->set("bigest_wspy_tweet_id", $tweet->id);
      }
    }

    $stories = Doctrine_Query::create()
            ->select("*")
            ->from("Story")
            ->where("id > ?",  $this->status->getConfig()->get("bigest_story_id", 0))
            ->addWhere("created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)")
            ->addWhere("type = 0")
            ->limit(500)
            ->execute();

    foreach($stories as $story)
    {
      $tweets = Doctrine_Query::create()
              ->select("*")
              ->from("WspyTweet")
              ->where("link = ?", $story->external_url)
              ->execute();

      foreach($tweets as $tweet)
      {
        try
        {
          $this->createStoryTweet($tweet, Doctrine::getTable("Story")->findOneByExternalUrl($tweet->link));
        }
        catch(Exception $e)
        {
          $this->log($e->getMessage());
        }
      }
    }
    if(isset($story) && false === empty($story->id))
    {
      $this->status->getConfig()->set("bigest_story_id", $story->id);
    }
  }

  public function preExit()
  {

  }

  public function preExecute()
  {

  }

  /**
   * Logs to console and to the log
   */
  public function log($msg)
  {
    echo $msg;
  }
}
?>