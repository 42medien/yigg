<?php

/**
 * security actions.
 *
 * @package    yigg
 * @subpackage security
 * @author     Your name here
 * @version    SVN: $Id: actions 9301 2008-05-27 01:08:46Z dwhittle $
 */
class securityActions extends sfActions
{
 /**
  * Executes index action
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex($request)
  {
    // check for post
    $this->form = new FormUserLogin();
    $userSession = $this->getUser();
    if( (true === $request->isMethod('post')) && (true === $this->form->processRequest()->isValid()) )
    {
      // Login was successful.
       $user = Doctrine::getTable("User")->findOneByUsername($this->form->getValue("username"));
       $this->getUser()->login($user, $this->form->getValue("remember"));
       return $this->redirect("@homepage");
    }
    elseif(  (true === $userSession->hasUser()) && (false === $userSession->isAdmin()) )
    {
      //  You need the correct credentials
      $this->getUser()->setFlash("login:error","Your currently logged in, but don't have security clearance.");
    }
    elseif( true === $request->isMethod('post') && $userSession->hasUser() )
    {
      // general authentication error.
      $this->getUser()->setFlash("login:error","Authentication failed");
    }
    elseif( true === $userSession->isTimedOut() )
    {
      // general authentication error.
      $this->getUser()->setFlash("login:error","Sorry, your session has timed out, please login again");
    }
    elseif (false === $userSession->hasUser())
    {
        $this->getUser()->setFlash("login:error","Please login first.");
    }
    else
    {
        $this->getUser()->setFlash("login:error","You don't have access to this section.");
    }
    return sfView::SUCCESS;
  }

  /**
   * logs the user out.
   *
   * @param yiggWebRequest $request
   * @return void
   */
  public function executeLogout($request)
  {
    $this->getUser()->logOut();
    $this->getUser()->setAuthenticated(false);
    $this->redirect('@homepage');
  }

  /**
   * returns the standard 404 page.
   *
   * @param unknown_type $request
   * @return unknown
   */
  public function execute404Successs($request)
  {
    return sfView::SUCCESS;
  }
}
