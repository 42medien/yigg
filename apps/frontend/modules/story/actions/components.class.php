<?php
class storyComponents extends sfComponents {

  public function executeLatestStoriesWidget(sfWebRequest $request)
  {
    $this->stories = StoryTable::retrieveLatestStories();
    return sfView::SUCCESS;
  }

  public function executeSponsorings(sfWebRequest $request)
  {
    switch( $request->getAction() )
    {
      case "bestStories":
        $this->place_id = 8;
      break;

      case "newStories":
        $this->place_id = 9;
      break;

      default: case "show":
        $this->place_id = 38;
        break;
    }

    return sfView::SUCCESS;
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
