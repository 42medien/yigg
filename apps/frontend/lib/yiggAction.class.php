<?php

/**
 * Every action should extend this for helper functions supporting
 * - validation
 * - filters
 * - redirection.
 *
 */
class yiggActions extends sfActions
{
  public function preExecute()
  {
    $this->session = $this->getUser();
    if(true === $this->session->hasUser() && $this->getActionName() !== "latestNodes")
    {
        $log = new UserOnlineLog;
        $log->user_id = $this->session->getUserId();
        $log->save();
    }
  }

  /**
   * Returns true if the request is an XmlHTTPrequest.
   * @return boolean
   */
  public function isAjaxRequest()
  {
    $request = $this->getRequest();
    return $request->isXmlHttpRequest();
  }

  /**
   * Logs a variable, object or array
   * @param mixed $obj
   */
  public function logVarDump($obj, $level="warning")
  {
    ob_start();
    var_dump($obj);
    $this->logMessage(ob_get_clean(), $level);
  }

  /**
   * Overwrites the default 404-message to contain module / action
   * @see lib/1.2/action/sfAction#get404Message($message)
   */
  protected function get404Message($message = null)
  {
    return sprintf('Error 404 in action "%s/%s" on URI: %s. (%s)',
                   $this->getModuleName(),
                   $this->getActionName(),
                   parse_url($this->getRequest()->getUri(), PHP_URL_PATH),
                   $message);
  }

  /**
   * Set the pagination for this (super) action.
   * @return
   * @param $query Object
   */
  public function setPagerQuery($query, $url=null)
  {
    $this->currentpage = $this->request->hasParameter("page") ? intval($this->request->getParameter("page")) : 1;
	  if(true === empty($url))
    {
      $url = $this->request->forceParams(array());
    }


    $this->pager = new firstLastStorysPager(
      // build the pager from the request information
      new Doctrine_Pager(
        $query,

          // current page of request
          $this->currentpage,

          // (optional) number of results per page default is 25
          empty( $this->limit ) ? 10 : $this->limit
        ),

        // create the options for the sliding ranger
        new Doctrine_Pager_Range_Sliding(
          // chunk length
          array(
            'chunk' => 7
          ),
          $this->pager
        ),
        // add the link format for the routing
        (false === strpos($url, "?")) ? $url . "?page={%page}" : $url . "&page={%page}"
    );

    return $this->pager;
  }

  /**
   * returns the yiggSession if their is a user and the user is logged in.
   *
   * @return yiggSession
   */
  public function securePage($msg = null)
  {
    $userSession = $this->getUser();
    if( false ===  $userSession->isTimedOut() && true === $userSession->hasUser())
    {
      return $userSession;
    }
    else if( true === $userSession->isTimedOut() )
    {
      $this->redirectLoginWithMessage('Du wurdest abgemeldet. Bitte melde dich erneut an.');
    }

    $this->redirectLoginWithMessage( $msg == null ? 'Du musst angemeldet sein, um diese Funktion zu nutzen.' : $msg );
  }

  /**
   * processes the filters on the current request based on a shared
   * class variable, and returns the paramter holder for futher processing.
   *
   * @param yiggWebRequest $request
   * @return sfParameterHolder
   */
  protected function processFilters( $request )
  {
    $parameterHolder = $request->getParameterHolder();
    if($this->filters)
    {
      $this->setDefaults( $parameterHolder, "filter");
    }

    if($this->sorts)
    {
      $this->setDefaults( $parameterHolder, "sort");
    }

    // filters below are parsed with yiggTools::parseString and spaces replaced with -
    $this->filterForm = new yiggPageFilter(
      $defaults = array( "filter" => $this->filter, "sort" => $this->sort ),
      $options = array( "filters" => $this->filters, "sorts" => $this->sorts )
    );

    $this->filterForm->processRequest();

    if( $this->filterForm->offsetExists("filter") )
    {
      $this->filter =  $this->filterForm->getValue('filter') ? $this->filterForm->getValue('filter') : $this->filter ;
      $parameterHolder->set("filter", $this->filter);
      if( $this->filterForm->getValue('filter'))
      {
        $this->getUser()->setAttribute('filter', $this->filter);
      }
    }

    if( $this->filterForm->offsetExists("sort") )
    {
      $this->sort = $this->filterForm->getValue('sort') ? $this->filterForm->getValue('sort') : $this->sort;
      $parameterHolder->set("sort", $this->sort );
      if( $this->filterForm->getValue('sort'))
      {
        $this->getUser()->setAttribute('sort', $this->sort);
      }
    }

    $parameterHolder->remove("PageFilter");
    $parameterHolder->remove("commit");
    return $parameterHolder;
  }

  /**
   * sets the defaults for the filterform, but making the first
   * element in the filterform array selected by default.
   *
   * @param unknown_type $parameterHolder
   * @param unknown_type $defaultName
   */
  private function setDefaults( $parameterHolder, $defaultName)
  {
    $varname = $defaultName . 's' ;
    if( array_key_exists( $parameterHolder->get( $defaultName ), $this->$varname ) )
    {
      $this->$defaultName = $parameterHolder->get( $defaultName );
    }
    else
    {
      $keys = array_reverse( array_keys( $this->$varname ) );
      $this->$defaultName = array_pop( $keys );
    }
  }

  /**
   * check validation for one field using the form object
   * return header only on ajax requests
   *
   * @param  yiggForm   $form
   * @param  string    $field
   * @return  boolean / string
   */
  public function checkFieldValidation( $form, $field )
  {
    // get id for field from attributes
    $fieldId  = $form[$field]->getWidget()->getAttribute('id');

    // get request value
    $value = $this->getRequest()->getParameter( $fieldId );

    // process form validation and get field error
    $error = $form->processField($field, $value)->getError();

    if( $this->isAjaxRequest() )
    {
      //$ninjaUpdater = $this->getRequest()->getNinjaUpdater();
      //$ninjaUpdater->updateForm( $fieldId, $value, $error );
      //$ninjaUpdater->attachJSONNinjaHeader( $this->getResponse() );
      //return sfView::HEADER_ONLY;
    }
    else
    {
      return empty($error) ? true : false;
    }
  }

  /**
   * sends a 403 response
   * @return sfView::NONE;
   */
  public function send403()
  {
    $response = $this->getContext()->getResponse();
    $response->setStatusCode(403);
    $response->setHeaderOnly( true );
    $response->sendHttpHeaders();
    return sfView::NONE;
  }

  /**
   * Allows you to redirect to the login page with a message eaiser.
   * @return
   * @param $message Object[optional]
   */
  public function redirectLoginWithMessage( $message = null )
  {
    return $this->redirectWithMessage($message, "@user_login", 'msg:login');
  }

  /**
   * redirect or return depending if request is ajax or not, target and msg_scope are
   * ignored on ajax requests
   *
   * @param     string    $message
   * @param     string    $target
   * @param     string    $msg_scope
   */
  public function quitActionWithMessage($message, $target, $msg_scope = 'msg:global')
  {
    if( true === $this->isAjaxRequest() )
    {
      return $this->renderText($message);
    }
    return $this->redirectWithMessage($message, $target, $msg_scope);
  }

  /**
   * redirect with a message, saving message to $scope and referrer in $referrer as
   * Session-Flash-Values
   *
   * @param     string    $message
   * @param     string    $target
   * @param     string    $msg_scope
   */
  public function redirectWithMessage($message, $target, $msg_scope = 'msg:global' )
  {
    $routing = $this->getContext()->getRouting();
    $referer = $routing->getCurrentInternalUri(true);

    $user     = $this->getUser();

    // set flash message
    $user->setFlash( $msg_scope, $message );

    // set referrerr for redirection (sfRequestHistory)
    $user->setAttribute( 'referer', $referer);

    if( true === $this->getRequest()->isAjaxRequest() )
    {
      $controller=  $this->getController();
      $url = $controller->genurl($target);

      $params = $routing->parse(str_replace($this->getRequest()->getScriptName(),"",$url));
      $this->getRequest()->getParameterHolder()->add($params);
      return $this->forward($params['module'],$params['action']);
    }

    // do redirection
    return $this->redirect($target);
  }

  /**
   * Returns the user back to the original action.
   */
  public function redirectBack()
  {
    if( true === $this->isAjaxRequest() )
    {
      return sfView::HEADER_ONLY;
    }

    $referer = $this->getRequest()->getReferer();
    return $this->redirect( $referer );
  }

  /**
   * Returns the successview with or without layout depending on if the
   * request was Ajax.
   */
  public function getView()
  {
    if( $this->isAjaxRequest() )
    {
      $this->setLayout(false);
    }
    return sfView::SUCCESS;
  }

  /**
   * Populates the Story collection with related attributes from a cache stored in session.
   * e.g. if a user votes on a story, that is stored in session, instead of doing a seperate db
   * lookup. (they still can't vote twice, but it lesses load, and speeds up page load time and reduces
   * db lookups considerably.
   *
   * @param Doctrine_Collection $story_collection_name
   */
  public function populateStoryAttributeCache( $story_collection_name = "stories")
  {
    // load the Ratings, if not present already.
    $userSession = $this->getUser();
    if(!$userSession->hasAttribute("StoryRatings","StoryRatings"))
    {
      $userSession->preCacheRatings();
    }

    // get the Ratings. but check the cache first.
    $storyRatings = $userSession->getAttribute("StoryRatings", array() ,"StoryRatings");
    if(!is_array($storyRatings))
    {
      $storyRatings = array();
    }

    $toCheck = array();
    $authors = array();
    foreach( $this->$story_collection_name as $story )
    {
      // compile a list of ratings to check/
      if( false === array_key_exists( $story['id'], $storyRatings) )
      {
        $toCheck[] = $story['id'];
      }
      else
      {
        $story->setHasRated(true);
      }
      $authors[] = $story->user_id;
    }

    // Only check the ones which we don't have in the cache.
    $authorRelations = UserTable::retrieveUsersRelatedFor( array_unique($authors) );
    $newRatings = count($toCheck) > 0 ? StoryRatingTable::retrieveHasRatedOn($toCheck, $userSession->hasUser() ? $userSession->getUser(): null ) : new Doctrine_Collection("Story");

    foreach(  $this->$story_collection_name as $story )
    {
      // only check it if we have haven't assigned it already.
      if( null === $story->hasRated() )
      {
        // if it has been voted on already
        if(true === array_key_exists($story['id'], $newRatings))
        {
          // update the session cache.
          $storyRatings[$story['id']] = $newRatings[ $story['id'] ];
          $story->setHasRated(true);
        }
        else
        {
          $story->setHasRated(false);
        }
      }

      // set the related elements for the authors.
      $author = $authorRelations[$story['user_id']];
      $story->setRelated("Author", $author );

      if($author->Permissions instanceof Doctrine_Collection)
      {
        $story->Author->setRelated("Permissions", $author->Permissions );
      }

      if($author->UserStats instanceof Doctrine_Collection)
      {
        $story->Author->setRelated("UserStats", $author->UserStats);
      }

      //$story->Author->setRelated("Avatar", $authorRelations[$story['user_id']]->Avatar);
    }
    // update with any new information.
    $userSession->setAttribute("StoryRatings", $storyRatings,"StoryRatings");
  }

  /**
   * Populates all the related attributes for comments
   * (speeds up requests considerably).
   * @return void
   */
  public function populateCommentAttributeCache()
  {
    foreach($this->comments as $comment)
    {
      $authors[] = $comment['user_id'] ;
      $comment_ids[] = $comment['id'];
    }

    $authorRelations = UserTable::retrieveUsersRelatedFor( array_unique($authors) );

    foreach($this->comments as $comment )
    {
      // set the related elements for the authors.
      $comment->setRelated("Author", $authorRelations[ $comment['user_id'] ]);
      $comment->setRelated("Story", $this->story );
      $comment->Author->setRelated("Permissions", $authorRelations[ $comment['user_id']]->Permissions );

      if($authorRelations[ $comment['user_id']]->UserStats instanceof Doctrine_Collection)
      {
        $comment->Author->setRelated("UserStats", $authorRelations[ $comment['user_id']]->UserStats);
      }

      //$comment->Author->setRelated("Avatar", $authorRelations[ $comment['user_id']]->Avatar);
    }
  }
}