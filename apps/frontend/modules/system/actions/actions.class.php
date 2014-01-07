<?php

class systemActions extends yiggActions
{
  /**
   * Redirects a user with a 301 when a URL has been
   * identified as permanently moved.
   *
   * @param unknown_type $request
   * @return unknown
   */
  public function executeRedirect($request)
  {
    $parameterHolder = $request->getParameterHolder();
    return $this->redirect($parameterHolder->get("to"), 301);
  }

  public function executeServeStatic($request)
  {
    $this->setTemplate("staticPages/".$request->getParameter("template"));
    return sfView::SUCCESS;
  }

  /**
   * Error page for page not found (404) error
   *
   */
  public function executeError404()
  {
    return sfView::SUCCESS;
  }

  /**
   * Warning page for restricted area - requires login
   *
   */
  public function executeSecure()
  {}

  /**
   * Warning page for restricted area - requires credentials
   *
   */
  public function executeLogin()
  {}

  /**
   * Module disabled in settings.yml
   *
   */
  public function executeDisabled()
  {}

  /**
   * Sends a bugreport to us
   *
   */
  public function executeBugReport($request)
  {
    $this->form = new FormReportBug ();
    if($this->session->hasFlash("errorDetails"))
    {
      $this->details = $this->session->getFlash("errorDetails");
    }
    else
    {
      $this->details = array();
      $this->details["referer"] = $request->getReferer();
      $this->details["user_agent"] = is_null($request->getHttpHeader("USER_AGENT")) ? null : $request->getHttpHeader("USER_AGENT");
      $this->session->setFlash("errorDetails", $this->details);
    }

    if($this->form->processAndValidate())
    {
      $partial = new sfPartialView( sfContext::getInstance(), "system","bugReportMail", NULL);
      $partial->setPartialVars(
        array(
          "error_action" => $this->form->getValue("error_action"),
          "error" => $this->form->getValue("error"),
          "details" => $this->details));

      $this->getMailer()->sendEmail(
        'hacker@yigg.de',
        sprintf('[Bug-Report] - %s reported a new bug.', ($this->session->hasUser()) ? $this->session->getUser()->username : "anonymous" ),
        $partial->render()
      );

      $this->setTemplate("bugReportThanks");
  }

  return sfView::SUCCESS;
  }

  /**
   * display system stats
   */
  public function executeStats()
  {
    $this->stats = Doctrine::getTable("SystemStats")->findAll(Doctrine::HYDRATE_ARRAY);
    return sfView::SUCCESS;
  }
}
