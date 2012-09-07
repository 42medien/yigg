<?php

/**
 * worldspy actions.
 *
 * @package    yigg
 * @subpackage worldspy
 */
class worldspyActions extends yiggActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeWorldSpy($request)
  {
    $this->getResponse()->addMeta('robots','noindex, nofollow');
    $this->form = new FormWorldspyKeywords();

    if($request->hasParameter("query") && false !== $request->getParameter("query"))
    {
      $this->form->offsetUnset("_csrf_token");
      $this->form->processArray(array("keywords" => urldecode($request->getParameter("query"))));
    }

    if($this->form->isValid() || true == $this->form->processAndValidate())
    {
      $this->tags = array();
      foreach($this->form->getValue('keywords') as $tag)
      {
        $this->tags[] = "% $tag %";
      }
      $this->session->setAttribute("wspy:tags", $this->tags);
    }
    else
    {
      $this->session->setAttribute("wspy:tags", null);
    }

    $query = new Doctrine_Query();
    $query ->select("*")
           ->from("FeedEntry")
           ->orderBy("epoch_time DESC")
           ->limit(30);

    $query = $this->refineQueryWithTags($query);
    $this->nodeList = $query->execute();

    $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 4 ,'limit' => 1)));
    $this->getResponse()->setTitle("Weltspion: Was passiert gerade auf der Welt");
    $this->getResponse()->addMeta(
      "description",
      "Der Weltspion zeigt Live-Nachrichten aus tausenen von Quellen."
    );
    $this->getResponse()->addMeta(
      'keywords',
      "Weltspion, welt passiert, live-stream, live, feed, now, YiGG jetzt, jetzt, Welt, RSS, Quell"
    );
    return sfView::SUCCESS;
  }

    /**
     * check external url and return images
     *
     * @return   string
     */
    public function executeCheckExternal_url( $request )
    {
        if(false === $this->isAjaxRequest())
        {
            return $this->send403();
        }
        $url = $this->getRequest()->getParameter( 'external_url' );

        $yiggImageParser = new ImageParser();
        $images = $yiggImageParser->fetch($url, 100);
        $slider_html = get_partial('story/imageSlider', array("images" => $images));

        $ninjaUpdater = $request->getNinjaUpdater();
        $ninjaUpdater->updateFormFieldContent("carousel", $slider_html);
        $ninjaUpdater->attachJSONNinjaHeader( $this->getResponse() );

        return sfView::HEADER_ONLY;
    }

  /**
   * Finds any new nodes from a request.
   *
   * @param unknown_type $request
   */
  public function executeLatestNodes($request)
  {
    if( $this->isAjaxRequest() )
    {
      $lastEvent = $request->getParameter("timestamp");
      $timestamp = preg_replace( '/[^0-9.]/','', str_replace("-",".", $lastEvent));

      $query = new Doctrine_Query();
      $query ->select("*")
          ->from("FeedEntry")
          ->where("epoch_time > ?", $timestamp+0.1)
          ->orderBy("id DESC");
      $query = $this->refineQueryWithTags($query);

      $this->nodeList = $query->execute();

      if(count($this->nodeList) == 0)
      {
        $response = $this->getResponse();
        $response->setStatusCode(304);
        $response->setHeaderOnly();
        $response->sendHttpHeaders();
        return sfView::HEADER_ONLY;
      }
      return sfView::SUCCESS;
    }
    return $this->send403();
  }

  private function refineQueryWithTags($query)
  {
    $tags = $this->session->getAttribute("wspy:tags", null);
    if(isset($tags) && is_array($tags) && count($tags) > 0)
     {
       foreach($tags as $tag)
       {
         $query->addWhere("description LIKE ?", $tag);
         $query->addWhere("created > DATE_SUB(NOW(), INTERVAL 2 DAY)");
       }
    }
    return $query;
  }

  /**
   * Show the node from the request in the spy (as a get request.
   *
   * @param unknown_type $request
   */
  public function executeShowNode($request)
  {
    $this->getResponse()->addMeta('robots','noindex, nofollow');
    $node_id = (int) $request->getParameter("node_id");
    $this->node = Doctrine::getTable("FeedEntry")->findOneById($node_id);
    $this->forward404Unless( $this->node , "Dieser Eintrag ist leider nicht mehr im Weltspion vorhanden, da er älter als 24 Stunden ist." );
    return sfView::SUCCESS;
  }

  /**
   * Populates a form with the contents from the worldspy to yigg.
   *
   * @param unknown_type $request
   */
  public function executeCreateStoryFromNode( $request )
  {
    $node_id = (int) $request->getParameter("node_id");
    if( false === $this->session->hasUser() )
    {
      $this->setTemplate("login","user");
      $this->form = new FormUserLogin();
      if(false === $this->form->processAndValidate())
      {
        $this->url = $this->getController()->genUrl("@worldspy_create_story?node_id={$node_id}");
        return sfView::SUCCESS;
      }
    }

    $entry = Doctrine::getTable("FeedEntry")->findOneById($node_id);
    $this->forward404Unless( $entry !== false , "Dieser Eintrag ist leider nicht mehr im Weltspion vorhanden, da er älter als 24 Stunden ist." );

    $parameterHolder = $request->getParameterHolder();
    $parameterHolder->set("exturl", (string) $entry->long_link );
    $parameterHolder->set("exttitle", strip_tags($entry->title));
    $parameterHolder->set("view", "Normal");
    $parameterHolder->set("extdesc", strip_tags(mb_substr($entry->description, 0, 500)));

    return $this->forward('story','create');
  }
  
  public function executeTopStories($request)
  {
  	$query = Doctrine_Query::create()
                   ->select("*")
                   ->from("FeedEntry AS fe")
                   ->where("fe.in_newsroom IS NOT NULL")
                   ->addWhere("(SELECT COUNT(s.id) as cnt FROM Story AS s WHERE s.external_url = fe.long_link AND s.deleted_at IS NULL) = 0" )
                   ->orderBy("created DESC");
    $this->entries = $this->setPagerQuery($query)->execute();
    $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 4 ,'limit' => 1)));    
    return sfView::SUCCESS;
  }

  public function executeDeleteEntry($request)
  {
    $this->forward404Unless($this->session->isAdmin());

    $node = Doctrine::getTable("FeedEntry")->findOneById($request->getParameter("id"));
    $this->forward404Unless($node);

    $node->delete();
    return $this->redirectWithMessage("Eintrag gelöscht", "@worldspy_top");
  }
}