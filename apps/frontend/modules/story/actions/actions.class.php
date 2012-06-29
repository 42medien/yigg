<?php
class storyActions extends yiggActions
{
  public function preExecute()
  {
    parent::preExecute();
    if($this->getRequest()->getParameter("sf_format") === "atom")
    {
      return;
    }
    $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 39 ,'limit' => 1)));
  }


  /**
   * Shows the best stories (normal view)
   * @return
   */
  public function executeBestStories( $request )
  {
    $sf = new yiggStoryFinder();
    $user = $this->session->getUser();

    if(false !== $user)
    {
      $sf->confineWithMarkedForFrontpage($user->id);
    }
    else
    {
      $sf->confineWithMarkedForFrontpage();
    }
    $sf->sortByDate();
    $query = $sf->getQuery();
    $query->groupBy("s.id");

    $this->limit = 10;
    $this->stories = $this->setPagerQuery($query)->execute();

    $this->storyCount = count($this->stories->getKeys());
    if( $this->storyCount > 0 )
    {
      $this->populateStoryAttributeCache();
    }
    if($this->getRequest()->getParameter("sf_format") !== "atom")
    {
      $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 1 ,'limit' => 1)));
    }

    $this->getResponse()->setTitle('Die besten Nachrichten von heute');
    return sfView::SUCCESS;
  }

  /**
   * Executes the new-stories (queue)
   * @return
   */
  public function executeNewStories( $request )
  {
    $sf = new yiggStoryFinder();
    $sf->confineWithDate24();
    $sf->sortByDate();

    // just return query for pager creation
    $this->limit = 10;
    $this->stories = $this->setPagerQuery($sf->getQuery())->execute();

    $this->storyCount = count($this->stories);
    if( $this->storyCount > 0 )
    {
      $this->populateStoryAttributeCache();
    }

    if($this->getRequest()->getParameter("sf_format") !== "atom")
    {
      $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 2 ,'limit' => 1)));
    }
    return sfView::SUCCESS;
  }

  /**
   * Archive functionality for YiGG
   * @param $request
   * @return sfView
   */
  public function executeArchive($request)
  {
    // not really needed as routing enforces them to be integers.
    $this->year = intval($request->getParameter("year", false));
    $this->month = intval($request->getParameter("month", false));
    $this->day = intval($request->getParameter("day", false));

    if( false != $this->year && false != $this->month &&  false != $this->day)
    {
      // ensure a correct timestring, and show error accordingly
      $this->timestring = $this->year . "-" . $this->month ."-". $this->day;

      if(false !== strtotime($this->timestring))
      {
         // get stories, sort by rating algorithm and date as array
         $sf = new yiggStoryFinder();
         $sf->confineWithDateFrom($this->timestring);
         $sf->confineWithDateUntil($this->timestring);
         $sf->sortByRating();

         $this->limit = 10;
         $this->stories = $this->setPagerQuery($sf->getQuery())->execute();
         return sfView::SUCCESS;
       }
       return sfView::ERROR;
    }

    return sfView::SUCCESS;
  }

  /**
   * Shows the story from the URL parameters
   */
  public function executeShow(sfWebRequest $request)
  {
    $this->findOrRedirect($request);

    switch($request->getParameter("view"))
    {
      case "show":
        if($this->session->hasUser())
        {
          StoryRenderTable::trackStoryRender($this->story, $this->session->getUser());
        }
        if($this->story->type == 1)
        {
          $this->setTemplate("showArticle");
        }
        break;

      case "tweet":
        return $this->redirect($this->story->getTwitterLink());

      case "facebook":
        return $this->redirect(yiggTools::createFacebook($this->story->title, $this->story->getExternalShortUrl()));

      case "blacklist":
        if( false === (true === $this->session->hasUser() && true === $this->session->isModerator()) )
        {
          return $this->forward("system","secure");
        }
        $domain = DomainTable::getInstance()->findOneByHostname($this->story->getSourceHost());
        $domain->domain_status = "blacklisted";
        $domain->save();
          
        $this->session->setFlash("success_msg",$this->story->getSourceHost() . " ist jetzt auf der Blacklist.");
        break;
    }

    if( $this->isAjaxRequest() && $this->partial )
    {
      return $this->renderPartial( $this->partial, array( "story" => $this->story, "comments" => $this->comments, "form"=> $this->form, "stories" => $this->stories ));
    }

    $this->getResponse()->setTitle( $this->story->title, false);
    $this->getResponse()->addMeta('description', substr($this->story->getPlainTextDescription(), 0, 160));
    $this->getResponse()->addMeta(
      'keywords',
      implode(", ",
        array_merge(
          explode(",", $this->story->getTagsAsString()),
          yiggTools::extractNouns($this->story->title)
        )
      )
    );
    return sfView::SUCCESS;
  }

  /**
   * Creates the Story Object and saves all the data
   * @param yiggWebRequest
   * @return sfView
   */
  public function executeCreate( $request )
  {
    if(false === $this->getUser()->getUser()->hasPostingStoryPermissions())
    {
      //return sfView::ERROR;
    }

    $this->getResponse()->addMeta('robots',  'noindex, follow' );
    $exturl = $request->getParameter("exturl", false);
    $exturl = (false !== $exturl) ? yiggStringTools::utf8_urldecode($exturl) : $exturl;

    if(false === $request->isMethod("post") && $exturl !== false && true === yiggTools::isProperURL($exturl))
    {
      $existing_story = StoryTable::getTable()->findOneByExternalUrl($exturl);
      if(false !== $existing_story)
      {
        return $this->redirect($existing_story->getLinkWithDomain(), 301);
      }
    }
    $this->view = $request->getParameter("view");

    $this->story = new Story();
    $this->story->setStoryType($this->view);

    $this->form = new FormStoryEdit(array(),array( "story" => $this->story ));
    if(true === $this->form->processAndValidate())
    {
      $conn = Doctrine::getConnectionByTableName("Story");
      $conn->beginTransaction();

      $this->story->update(
        array_merge(
          $this->form->getValues(),
          array(
            "user_id" => $this->session->getUserId(),
          )
        ),
        $conn
      );

      $this->story->rate( $this->session, $conn);
      $this->story->save($conn);
      $conn->commit();

      return $this->redirect($this->story->getLink());
    }

    $title = $request->getParameter("exttitle", false);
    if(false !== $request->hasParameter("exturl", false) )
    {
      $yiggWebClient = new StoryTools();
      $title = $yiggWebClient->fetchTitleFromUrl( $request->getParameter("exturl") );
    }

    if(false !== $title )
    {
      $autotags = yiggTools::extractNouns($title);
    }

    $this->form->setDefaults(
      array(
        'external_url'  => (false === $exturl) ? $this->story->external_url : $exturl,
        'title'      => $request->getParameter("exttitle", false !== $title ? $title : ""),
        'description'  => $request->getParameter("extdesc", $this->story->description),
        'Tags'      => isset($autotags) && count($autotags) > 0 ? implode($autotags, ", ") : null,
      )
    );
    return sfView::SUCCESS;
  }

  /**
   * Edits the story in question.
   *
   */
  public function executeEdit( $request )
  {
    $this->findOrRedirect($request);

    $this->forward404Unless((true === $this->story->isAuthor($this->session)) || (true === $this->session->isAdmin()));
    $this->form = new FormStoryEdit(array(),array( "story" => $this->story ));

    $defaults = $this->story->toArray();
    $defaults = array_merge($defaults,
         array(
            'Tags'=> $this->story->getTagsAsString(),
            'description' => !empty( $this->story['description'] ) ? $this->story['description'] : $this->story->getPlainTextDescription()));

    $this->form->setDefaults($defaults);

    $schema = $this->form->getValidatorSchema();
    if($schema->offsetExists('external_url'))
    {
      $schema->offsetGet('external_url')->setOption( 'story', $this->story );
    }

    $formAction = $request->getParameter("formAction");

    if(is_array($formAction) && is_array(array_pop($formAction)) && array_pop(array_flip( $formAction )) === "cancel")
    {
      return $this->redirect($this->story->getLink());
    }

    if(true === $this->form->processAndValidate())
    {
      $conn = Doctrine::getConnectionByTableName("Story");
      $conn->beginTransaction();
        $result = $this->story->update( $this->form->getValues(), $conn);
      $conn->commit();
      return $this->redirect($this->story->getLink());
    }
    return sfView::SUCCESS;
  }

  /**
   * Delete is only be allowed by the
   * author
   * admin/moderator
   */
  public function executeDelete($request)
  {
    $this->findOrRedirect($request);

    if( (true == $this->story->isAuthor( $this->session ) ) || $this->session->isModerator() )
    {
      $this->story->delete();
      if( true === $this->isAjaxRequest() )
      {
        return sfView::NONE;
      }
      return sfView::SUCCESS;
    }
    return $this->forward("system","secure");
  }

  /**
   * Allows a story to be rated / points increased.
   * @return
   */
  public function executeRate($request)
  {
    // find the story for the component.
    $this->findOrRedirect($request);
    $this->renderComponent("story","rateStory", array("story"=> $this->story,"external" => true));
    return sfView::NONE;
  }

  /**
   * check external url and return header
   *
   * @return   string
   */
  public function executeCheckExternal_url( $request )
  {
    if(false === $this->isAjaxRequest())
    {
      return $this->send403();
    }

    $this->story = new Story();
    $this->form = new FormStoryEdit(array(), array( "story" => $this->story ));
    $url_is_valid =
      sfView::HEADER_ONLY === $this->checkFieldValidation($this->form, 'external_url')
      && true === $this->form['external_url']->hasError();

    $url = $this->getRequest()->getParameter( 'external_url' );
    $error = $this->form->processField('external_url', $url)->getError();

    $ninjaUpdater = $request->getNinjaUpdater();
    $ninjaUpdater->updateForm('external_url', $url, $error );
    $this->populateFieldsFromUrl();
    $ninjaUpdater->attachJSONNinjaHeader( $this->getResponse() );

    return sfView::HEADER_ONLY;
  }  

  /**
   * Populates the JSON data with content from the title attribute of the location.
   * @return void
   */
  public function populateFieldsFromUrl()
  {
    $url = $this->getRequest()->getParameter( 'external_url' );

    $e = new yiggMetaDataExtractor($url);
    $metaData = $e->getMetaTags();

    $title = $e->getTitle();
    $keywords = array_key_exists("keywords", $metaData) ? $metaData["keywords"] : "";
    $description = array_key_exists("description", $metaData) ? $metaData["description"] : "";

    $nouns_in_title = yiggTools::extractNouns($title. " " . $keywords . " " . $description);
    $nouns_in_title = array_unique($nouns_in_title);
    $tags = implode(", ", $nouns_in_title);

    $ninjaUpdater = $this->getRequest()->getNinjaUpdater();
    $ninjaUpdater->updateFormField("Title", $title);
    $ninjaUpdater->updateFormField("Description", $description);
    $ninjaUpdater->updateFormField("Tags", $tags);
  }


  public function executeExternalRate( $request )
  {
    $this->story = Doctrine::getTable("Story")->findOneByExternalUrl($request->getParameter("url"));

    if(false !== $this->story )
    {
      $this->renderComponent("story","rateStory", array( "story" => $this->story, "external"=> true, "flat" => (true === (bool) $request->getParameter("flat",false)) ));
      return sfView::NONE;
    }

    $this->renderPartial("createStoryButton", array( "video" => $this->is_video, "flat" => (true === (bool) $request->getParameter("flat",false)) ) );
    return sfView::NONE;
  }

  /**
   * Redirect old urls to new ones
   * @param sfWebRequest $request
   * @return void
   */
  public function executeRedirectOld(sfWebRequest $request)
  {
    return $this->findOrRedirect($request);
  }

  /**
   * Creates a story instantly
   * @param sfWebRequest $request
   * @return string
   */
  public function executeInstantCreate(sfWebRequest $request)
  {
    $values = array();
    $values["external_url"] = $request->getParameter("url");

    $story = StoryTable::getTable()->findOneByExternalUrl($values["external_url"]);
    if(false !== $story)
    {
      $this->redirect($story->getLink());
    }

    $e = new yiggMetaDataExtractor($values["external_url"]);
    $metaData = $e->getMetaTags();

    $values["description"] = substr($e->getReadableDescription(), 0, 500);
    $values["title"] = $e->getTitle();

    $keywords = array_key_exists("keywords", $metaData) ? $metaData["keywords"] : "";
    $nouns = yiggTools::extractNouns($values["title"]. " " . $keywords . " " . $values["description"]);
    $nouns = array_unique($nouns);
    $values["Tags"] = implode(", ", $nouns);

    $story = new Story();
    $story->type = Story::TYPE_NORMAL;
    
    $form = new FormStoryEdit(array(),array("story" => $story));
    unset($form["_csrf_token"]);
    $form->bind($values);

    if($form->isValid())
    {
      $story->user_id = $this->session->getUserId();
      $story->update($form->getValues());
      $story->rate($this->session);
      $story->save();

      return $this->redirect($story->getLink(), 301);
    }
    $this->redirect("/neu?exturl=".$values["external_url"]);
  }

  private function findOrRedirect($request)
  {
    $this->story = Doctrine::getTable("Story")->findOneByInternalUrl($request->getParameter("slug"));
    $this->forward404Unless( $this->story, "Diese Nachricht konnte nicht gefunden werden.");

    $creation_date_string = $this->story->getCreationYear().$this->story->getCreationMonth().$this->story->getCreationDay();
    $date_from_routing = $request->getParameter("year").$request->getParameter("month").$request->getParameter("day");

    if($creation_date_string !== $date_from_routing)
    {
      $this->redirect($this->story->getLink(), 301);
    }
  }
}