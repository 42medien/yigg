<?php
class storyActions extends yiggActions {
  public function preExecute() {
    parent::preExecute();
    if($this->getRequest()->getParameter("sf_format") === "atom") {
      return;
    }
  }

  /**
   * Shows story in iframe with yigg bar
   */
  public function executeStoryBar($request) {
    $this->findOrRedirect($request);
    $this->getContext()->getConfiguration()->loadHelpers(array('Url', 'Yigg'));

    $this->relatedStories = StoryTable::retrieveRelatedStories($this->story);

    $this->getResponse()->setTitle( $this->story->title, false );
    $this->getResponse()->addMeta('description', $this->story->getDescription());
    $this->getResponse()->addMeta('og:title', $this->story->getTitle());
    $this->getResponse()->addMeta('og:description', substr($this->story->getPlainTextDescription(), 0, 160));
    $this->getResponse()->addMeta('og:url', $this->story->getExternalUrl());
    $this->getResponse()->addMeta('og:type', 'website');

    if ($source = $this->story->getStoryImageSource()) {
      $this->getResponse()->addMeta('og:image', public_path($source, true));
    }

    $this->setLayout('layout.bar');
  }

  /**
   * Executes the category-stories (queue)
   * @return
   */
  public function executeCategoryStories( $request ) {
    $this->setLayout("layout.stream");
    $this->category = $this->getRoute()->getObject();

    $sf = new yiggStoryFinder();
    $sf->confineWithCategory($this->category->getId());
    $sf->sortByDate();

    // just return query for pager creation
    $this->limit = 10;
    $this->stories = $this->setPagerQuery($sf->getQuery())->execute();

    $this->storyCount = count($this->stories);
    if( $this->storyCount > 0 ) {
      $this->populateStoryAttributeCache();
    }
    return sfView::SUCCESS;
  }

  /**
   * Shows the best stories (normal view)
   * @return
   */
  public function executeBestStories( $request ) {
    $this->setLayout("layout.stream");

    $sf = new yiggStoryFinder();
    $user = $this->session->getUser();

    if (false !== $user) {
      $sf->confineWithMarkedForFrontpage($user->id);
    } else {
      $sf->confineWithMarkedForFrontpage();
    }

    $sf->confineWithDateFrom("-1 month");
    $sf->confineWithDateUntil("now");
    $sf->sortByYTTCS();

    $query = $sf->getQuery();
    $query->groupBy("s.id");

    $this->limit = 10;
    $this->stories = $this->setPagerQuery($query)->execute();

    $this->storyCount = count($this->stories->getKeys());
    if ( $this->storyCount > 0 ) {
      $this->populateStoryAttributeCache();
    }
    if ($this->getRequest()->getParameter("sf_format") !== "atom") {
      $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 1 ,'limit' => 1)));
    }

    $this->getResponse()->setTitle('Die besten Nachrichten von heute');
    return sfView::SUCCESS;
  }

  /**
   * Executes the new-stories (queue)
   * @return
   */
  public function executeNewStories( $request ) {
    $this->setLayout("layout.stream");
    $sf = new yiggStoryFinder();
    $sf->sortByDate();

    // just return query for pager creation
    $this->limit = 10;
    $this->stories = $this->setPagerQuery($sf->getQuery())->execute();

    $this->storyCount = count($this->stories);
    if ( $this->storyCount > 0 ) {
      $this->populateStoryAttributeCache();
    }

    if ($this->getRequest()->getParameter("sf_format") !== "atom") {
      $this->getResponse()->setSlot('sponsoring', $this->getComponent("sponsoring","sponsoring", array( 'place_id' => 2 ,'limit' => 1)));
    }
    return sfView::SUCCESS;
  }

  /**
   * Archive functionality for YiGG
   * @param $request
   * @return sfView
   */
  public function executeArchive($request) {
    $this->setLayout("layout.stream.full");
    $this->year = intval(date("Y", time()));

    $year = $request->getParameter("year");
    $month = $request->getParameter("month");
    $day = $request->getParameter("day");

    $this->year = $year;
    $this->month = $month;
    $this->day = $day;

    if ($day) {
      $this->end_timestring = $this->start_timestring = $year."-".$month."-".$day;
    } elseif($month) {
      $this->start_timestring = $year."-".$month."-01";
      $this->end_timestring = $year."-".$month."-31";
    } elseif ($year) {
      $this->start_timestring = $this->year."-01-01";
      $this->end_timestring = $this->year."-12-31";
    } else {
      $this->start_timestring = date("Y-m-d", strtotime("now"));
      $this->end_timestring = date("Y-m-d", strtotime("now"));

      $this->year = date("Y", time());
      $this->month = date("m", time());
      $this->day = date("d", time());
    }

    if (false !== strtotime($this->start_timestring) && false !== strtotime($this->end_timestring)) {
      // get stories, sort by rating algorithm and date as array
      $sf = new yiggStoryFinder();
      $sf->confineWithDateFrom($this->start_timestring);
      $sf->confineWithDateUntil($this->end_timestring);
      $sf->sortByRating();

      $this->limit = 50;
      $this->stories = $this->setPagerQuery($sf->getQuery())->execute();
      return sfView::SUCCESS;
    }

    return sfView::SUCCESS;
  }

  /**
   * Shows the story from the URL parameters
   */
  public function executeShow(sfWebRequest $request) {
    $this->setLayout("layout.stream");
    $this->findOrRedirect($request);

    $this->relatedStories = Doctrine_Core::getTable('Story')->retrieveRelatedStories($this->story);

    switch($request->getParameter("view")) {
      case "show":
        if($this->session->hasUser()) {
          StoryRenderTable::trackStoryRender($this->story, $this->session->getUser());
        }
        if($this->story->type == 1) {
          $this->setTemplate("showArticle");
        }
        break;

      case "tweet":
        return $this->redirect($this->story->getTwitterLink());

      case "facebook":
        return $this->redirect(yiggTools::createFacebook($this->story->title, $this->story->getExternalShortUrl()));

      case "blacklist":
        if ( false === (true === $this->session->hasUser() && true === $this->session->isModerator()) ) {
          return $this->forward("system","secure");
        }
        $domain = DomainTable::getInstance()->findOneByHostname($this->story->getSourceHost());
        $domain->domain_status = "blacklisted";
        $domain->save();

        $this->session->setFlash("success_msg",$this->story->getSourceHost() . " ist jetzt auf der Blacklist.");
        break;
    }

    if( $this->isAjaxRequest() && $this->partial ) {
      return $this->renderPartial( $this->partial, array( "story" => $this->story, "comments" => $this->comments, "form"=> $this->form, "stories" => $this->stories ));
    }

    $this->embed_code = $this->story->getEmbedCode();

    $this->getResponse()->setTitle( $this->story->title, false );
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

    $this->getResponse()->addMeta('og:title', $this->story->getTitle());
    $this->getResponse()->addMeta('og:description', substr($this->story->getPlainTextDescription(), 0, 160));
    $this->getResponse()->addMeta('og:type', 'article');
    $this->getResponse()->addMeta('twitter:card', 'summary');

    return sfView::SUCCESS;
  }

  /**
   * Creates the Story Object and saves all the data
   * @param yiggWebRequest
   * @return sfView
   */
  public function executeCreate( $request ) {
    $userModel = new User;
    if(true === $userModel->isPostStoryBlocked($this->session->getUserId())) {
      return sfView::ERROR;
    }

    $this->getResponse()->addMeta('robots',  'noindex, follow' );
    $exturl = $request->getParameter("exturl", $request->getParameter("url", null));
    $exturl = (false !== $exturl) ? yiggStringTools::utf8_urldecode($exturl) : $exturl;

    if(false === $request->isMethod("post") && $exturl !== false && true === yiggTools::isProperURL($exturl)) {
      $existing_story = StoryTable::getTable()->findOneByExternalUrl($exturl);
      if(false !== $existing_story) {
        return $this->redirect($existing_story->getLinkWithDomain(), 301);
      }
    }
    $this->view = $request->getParameter("view");

    $this->story = new Story();
    $this->story->setStoryType($this->view);
    $this->form = new FormStoryEdit(array(),array( "story" => $this->story ));

    if (true === $this->form->processAndValidate()) {
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

      $this->story->updateCategories();

      $story_image = $this->form->getValue("image_slider");

      if ($story_image) {
        $tmpfname = tempnam(sfConfig::get('sf_upload_dir'), "SL");
        $ext = pathinfo($story_image, PATHINFO_EXTENSION);
        $image = getimagesize($story_image);
        $mime = $image['mime'];

        file_put_contents($tmpfname, file_get_contents($story_image));

        $validatedFile = new sfValidatedFile('image'.$ext, $mime, $tmpfname, filesize($tmpfname));

        if( !empty($validatedFile) && $validatedFile->getSize() > 0 ) {
          try {
            $file = File::createFromValidatedFile( $validatedFile, "stories","story-". $this->story->getId() );
          }
          catch(Exception $e ) {
            $this->logMessage(sprintf("Adding image story failed for %s. Error: %s", $this->story->getId(), $e->getMessage()));
          }

          if( isset($file) && $file->id ) {
            $this->story->image_id = $file->id;
          }
        }
      }
      $this->story->save();

      $conn->commit();

      return $this->redirect($this->story->getLink());
    }

    $title = $request->getParameter("exttitle", false);
    if ( false !== $request->hasParameter("exturl", false) ) {
      $yiggWebClient = new StoryTools();
      $title = $yiggWebClient->fetchTitleFromUrl( $request->getParameter("exturl") );
    }

    if(false !== $title ) {
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
  public function executeEdit( $request ) {
    $this->findOrRedirect($request);

    $this->forward404Unless((true === $this->story->isAuthor($this->session)) || (true === $this->session->isAdmin()));
    $this->form = new FormStoryEdit(array(), array( "story" => $this->story ));

    $defaults = $this->story->toArray();
    $defaults = array_merge(
                  $defaults,
                  array(
                    'Tags'=> $this->story->getTagsAsString(),
                    'description' => !empty( $this->story['description'] ) ? $this->story['description'] : $this->story->getPlainTextDescription()
                  )
                );

    $this->form->setDefaults($defaults);

    $schema = $this->form->getValidatorSchema();

    if ($schema->offsetExists('external_url')) {
      $schema->offsetGet('external_url')->setOption( 'story', $this->story );
    }

    $formAction = $request->getParameter("formAction");

    if (is_array($formAction) && is_array(array_pop($formAction)) && array_pop(array_flip( $formAction )) === "cancel") {
      return $this->redirect($this->story->getLink());
    }

    if (true === $this->form->processAndValidate()) {
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
  public function executeDelete($request) {
    $this->findOrRedirect($request);

    if ( (true == $this->story->isAuthor( $this->session ) ) || $this->session->isModerator() ) {
      $this->story->delete();
      if ( true === $this->isAjaxRequest() ) {
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
  public function executeRate($request) {
    // find the story for the component.
    $this->findOrRedirect($request);
    $this->renderComponent("story", "rateStory", array("story"=> $this->story, "external" => true, "flat" => (boolean) $request->getParameter("flat", false)));
    return sfView::NONE;
  }

  /**
   * check external url and return header
   *
   * @return   string
   */
  public function executeCheckExternal_url( $request ) {
    if(false === $this->isAjaxRequest()) {
      return $this->send403();
    }

    $node_id = (int) $request->getParameter("node");
    $url = $this->getRequest()->getParameter( 'external_url' );

    if ($node_id) {

    } else {
      $this->story = new Story();
      $this->form = new FormStoryEdit(array(), array( "story" => $this->story ));
      $url_is_valid = sfView::HEADER_ONLY === $this->checkFieldValidation($this->form, 'external_url') && true === $this->form['external_url']->hasError();

      $error = $this->form->processField('external_url', $url)->getError();

      $ninjaUpdater = $request->getNinjaUpdater();
      $ninjaUpdater->updateForm('external_url', $url, $error );
      $this->populateFieldsFromUrl();
      $ninjaUpdater->attachJSONNinjaHeader( $this->getResponse() );
    }

    return sfView::HEADER_ONLY;
  }

  /**
   * Populates the JSON data with content from the title attribute of the location.
   * @return void
   */
  public function populateFieldsFromUrl() {
    $url = $this->getRequest()->getParameter( 'external_url' );

    $e = new yiggMetaDataExtractor($url);

    $title = $e->getTitle();
    $keywords = $e->getMetaTags();
    $description = $e->getReadableDescription();
    $images = $e->getImages();
    $tags = $e->getMetaTags();

    $this->getContext()->getConfiguration()->loadHelpers(array('Partial'));

    $slider_html = get_partial('story/imageSlider', array("images" => $images));

    $ninjaUpdater = $this->getRequest()->getNinjaUpdater();
    $ninjaUpdater->updateFormField("Title", $title);
    $ninjaUpdater->updateFormField("Description", $description);
    $ninjaUpdater->updateFormField("Tags", $tags);
    $ninjaUpdater->updateFormFieldContent("carousel", $slider_html);
  }


  public function executeExternalRate( $request ) {
    $this->story = Doctrine::getTable("Story")->findOneByExternalUrl(urldecode($request->getParameter("url")));
    $this->flat = (bool) $request->getParameter("flat", false);

    $this->setLayout(false);
  }

  /**
   * Redirect old urls to new ones
   * @param sfWebRequest $request
   * @return void
   */
  public function executeRedirectOld(sfWebRequest $request) {
    return $this->findOrRedirect($request);
  }

  /**
   * Creates a story instantly
   * @param sfWebRequest $request
   * @return string
   */
  public function executeInstantCreate(sfWebRequest $request) {
    $values = array();
    $values["external_url"] = $request->getParameter("url");

    $story = StoryTable::getTable()->findOneByExternalUrl($values["external_url"]);
    if(false !== $story) {
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

    if ($form->isValid()) {
      $story->user_id = $this->session->getUserId();
      $story->update($form->getValues());
      $story->rate($this->session);
      $story->save();

      return $this->redirect($story->getLink(), 301);
    }
    $this->redirect("/neu?exturl=".$values["external_url"]);
  }

  private function findOrRedirect($request) {
    $this->story = Doctrine::getTable("Story")->findOneByInternalUrl($request->getParameter("slug"));
    $this->forward404Unless( $this->story, "Diese Nachricht konnte nicht gefunden werden.");

    $creation_date_string = $this->story->getCreationYear().$this->story->getCreationMonth().$this->story->getCreationDay();
    $date_from_routing = $request->getParameter("year").$request->getParameter("month").$request->getParameter("day");

    if($creation_date_string !== $date_from_routing) {
      $this->redirect($this->story->getLink(), 301);
    }
  }
}