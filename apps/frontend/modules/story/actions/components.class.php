<?php
class storyComponents extends sfComponents {

  public function executeLatestStoriesWidget(sfWebRequest $request)
  {
    $this->stories = StoryTable::retrieveLatestStories();
    return sfView::SUCCESS;
  }
  
  public function executeTwitterShares($request) {
    $consumer = new OAuthConsumer("zjpUwdOQ3Gm3IIo2gPUSHw", "72sEMdRACQZvHQL4JEP1khqHDBVXJuwkIgJY1xK2U");
    $url = "https://api.twitter.com/1.1/search/tweets.json?q=".$this->url;
    $json = OAuthClient::get($consumer, "2875011-HUgE5VFMoIpiYLTHWKUkfI5So7nBBJId8iikrDLbem", "CP41G2jguVUSRpQyA85wRzulkPN98tdS82XV7VWWk", $url);
    $obj = json_decode($json);
    
    if (isset($obj->statuses)) {
      $this->tweets = $obj->statuses;
    } else {
      return sfView::NONE;
    }
  }    

  /**
   * Story Rate component form.
   * @return
   * @param $request Object
   */
  public function executeRateStory($request)
  {
    $this->form = new FormStoryRate();
    if( true === $this->form->processAndValidate() )
    {
      $conn = Doctrine::getConnectionByTableName("Story");
      $conn->beginTransaction();
      $this->story->rate( $this->getUser() , $conn);
      $conn->commit();

      if( true === $request->isAjaxRequest() )
      {
        return sfView::SUCCESS;
      }

      $referer = $request->getReferer();
      if($referer)
      {
        return $this->getContext()->getController()->redirect( $referer );
      }

      $key = sfRequestHistory::getCurrentRequestKey();
      $uri = sfRequestHistory::getRequestKeyUri( ($key > 0 ? $key : 0)) ;
      $current = sfRequestHistory::getCurrentUri();
      while( ($uri == $current) && ($key >= 0) )
      {
        $uri = sfRequestHistory::getRequestKeyUri( --$key ) ;
      }

      $routing = $this->getContext()->getRouting();
      $params = $routing->parse($uri);

      if($params['action'] == "externalRate")
      {
        return $this->getContext()->getController()->redirect( "@stories_button_id?id=". $this->story->id, 301);
      }

      return $this->getContext()->getController()->redirect( $params );
    }
    return sfView::SUCCESS;
  }
}
