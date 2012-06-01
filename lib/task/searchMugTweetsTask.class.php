<?php
class searchMugTweetsTask extends yiggTaskToolsTask
{

  protected function configure()
  {
    $this->namespace        = 'twitter';
    $this->name             = 'updateMugTweets';
    $this->briefDescription = 'Search for new tweets containing yigg.it';
    $this->addArgument('application', sfCommandArgument::OPTIONAL, 'The application name', 'frontend');
    $this->addOption('env', null, sfCommandOption::PARAMETER_REQUIRED, 'The environment', 'cli');

  }

  public function preExecute()
  {
    $this->exitOnHighLoadAverage();
  }

  public function preExit()
  {
    $this->log("Stopping updateMugTweetsTask. This is the last line.");
  }

  public function executeWork($arguments = array(), $options = array())
  {
    $oauth_token = "2875011-RcTpa614fNr0c6yKYvJ9Z9karLLTFCaQGmbgW1sEgU";
    $oauth_secret = "reqC3Ej2nShGRCDkOHTxUimCJaYQN41Z9iAr5t8T8C4";
    $twitter = new yiggTwitterOauth($oauth_token, $oauth_secret);

    $params = array("count" => 200, "since_id" => $this->status->getConfig()->get("mentions_since_id", 1));
    $tweets = $twitter->getStatusesMentions($params);
    if(count($tweets) > 0)
    {
      $this->status->getConfig()->set("mentions_since_id", $tweets[0]["id"]);
      $this->processTweets($tweets);
    }
    $tweets = $twitter->search("yigg.it", array("count" => 200));
    $this->processTweets($tweets);
  }

  private function  processTweets($tweets)
  {
    foreach($tweets as $tweet)
    {
      Tweet::createByJsonTweet($tweet);
    }
  }
}