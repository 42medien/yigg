<?php
class FeedEntry extends BaseFeedEntry
{
  /**
   * Get the name of this Entry
   * @return mixed
   */
  public function getFeedName()
  {
    return parse_url($this->long_link, PHP_URL_HOST);
  }

  /**
   * Create a Story from this FeedEntry and link Tweets to the Story
   * @param  $user_id
   * @return bool
   */
  public function createStoryFromEntry($user_id)
  {
      if(false !== Doctrine::getTable("Story")->findOneByExternalUrl($this->long_link))
      {
      	return false;
      }

      $description = strip_tags($this->description);
      $description = yiggStringTools::html_entity_decode($description);
      $description = substr($description,0,600);

      $story = new Story();
      $form = new FormStoryEdit(array(), array('story' => $story));
      $form->offsetUnset("_csrf_token");

      $autotags = yiggTools::extractNouns($this->title);

      $form->processArray(
        array(
          'title' => $this->title ,
          'external_url' => $this->long_link ,
          'description' => $description,
          'Tags' => isset($autotags) && count($autotags) > 0 ? implode($autotags, ", ") : "worldspy")
      );

      if( false === $form->isValid() )
      {
        return false;
      }

      $story->user_id = $user_id;

      $conn = Doctrine::getConnectionByTableName("Story");
      $conn->beginTransaction();
        $story->update( $form->getValues() , $conn);
        $story->initalVote();
      $conn->commit();
      return true;
   }

  public function getShortUrl()
  {
    $redirect = RedirectTable::getByUrl($this->long_link);
    if($redirect === false)
    {
      $redirect = Redirect::create($this->long_link);
    }
    return $redirect->getMiniUri();
  }

  public function qualifiesForYiggStory()
  {
    return $this->hasAtLeastTweets(4);
  }

  public function qualifiesForNewsroom()
  {
    return $this->hasAtLeastTweets(1);
  }

  private function hasAtLeastTweets($number)
  {
    $tweets = Doctrine_Query::create()
                     ->from('WspyTweet')
                     ->where('created_at > DATE_SUB(NOW(), INTERVAL 12 HOUR)')
                     ->addWhere('link = ?', $this->long_link)
                     ->count();

    if($tweets >= $number)
    {
      return true;
    }
    return false;
  }
}
