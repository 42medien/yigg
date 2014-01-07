<?php
class Tweet extends BaseTweet{
    const tweetUrlFormat = "http://twitter.com/home/?status=%s";

	public function getRewtweetLink()
	{
	  $tweet = "RT @" . $this->username . ": " . $this->text;
	  return $this->getTweetLink($tweet);
	}

	public function getReplyLink()
	{
	  return $this->getTweetLink("@".$this->username);
	}

	public function getUserTwitterLink()
	{
		return sprintf("http://twitter.com/%s", $this->username);
	}

	public function getYiggTwitterProfileLink()
	{
		return "@twitter_user_profile?username=".$this->username;
	}
	

	public function getTweetLink($tweet)
	{
		return sprintf(self::tweetUrlFormat, urlencode(substr($tweet, 0, 140)));
	}


	/**
	 * Create tweet from json_response
	 * @param unknown_type $json_tweet
	 * @return Tweet if tweet was createt fals if error occured.
	 */
	public static function createByJsonTweet($json_tweet)
	{
	  if(TweetTable::tweetExists($json_tweet["id"]))
	  {
	    return false;
	  }

	  $tweet = new Tweet();
	  $tweet->fromArray(
        array(
  		  "twitter_id" => $json_tweet["id"],
    	  "text" => $json_tweet["text"],
		  "source" => $json_tweet["source"],
          "username" => false === empty($json_tweet["from_user"]) ? $json_tweet["from_user"] : $json_tweet["user"]["screen_name"],
		  "created_at" => date("Y-m-d H:i:s", strtotime($json_tweet["created_at"])),
    	  "profile_image_url" => false === empty($json_tweet["profile_image_url"]) ? $json_tweet["profile_image_url"] : $json_tweet["user"]["profile_image_url"],
        ));
        $tweet->save();
        return $tweet;
	}

	/**
	 * Links all relating objects to this tweet
	 */
	public function postInsert($event)
	{
	  $redirect = $this->findMatchingRedirect();
	  if(false === $redirect)
	  {
	    return;
	  }
	  $this->linkToStoryAndRate($redirect);

	}

  /**
   * Finds the matching redirect for a tweet
   * @param $tweet
   * @return Redirect
   */
   private function findMatchingRedirect()
   {
     preg_match("^http://yigg.it/([a-z0-9]{4,5})^si", $this->text, $matches);
     if(empty($matches[1]))
     {
       return false;
     }
     return RedirectTable::getTable()->findOneByRedirectKey($matches[1]);
   }

  /**
   * Links a tweet to the belonging story and rates
   * @param $tweet
   * @param $redirect
   */
  private function linkToStoryAndRate($redirect)
  {
    $story = Doctrine::getTable("Story")->findOneByExternalUrl($redirect->url);

	if(false === $story)
	{
	  return;
	}
	$storyTweet = new StoryTweet();
	$storyTweet->story_id = $story->id;
    $storyTweet->tweet_id = $this->id;
    $storyTweet->save();

	$rating = new Rating();
	$rating->ip_address = "127.0.0.2";
	$rating->user_agent = sprintf("Twitter.com (%s)", $this->username);
    $story->Ratings->add($rating);
    $story->save();
  }
}
