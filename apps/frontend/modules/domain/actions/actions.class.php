<?php
class domainActions extends yiggActions
{
  public function executeShow(sfWebRequest $request) {
    $this->setLayout("layout.stream");
    $this->domain = DomainTable::getInstance()->findOneById($request->getParameter("id"));
    $this->forward404Unless($this->domain);
    
    $query = StoryTable::getQueryByDomain($this->domain);
    $this->stories = $this->setPagerQuery($query)->execute();
  }

  /**
   * Subscribe a user to this domain
   * @param sfWebRequest $request
   * @return redirectBack()
   */
  public function executeChangeSubscription(sfWebRequest $request)
  {
    $domain = DomainTable::getInstance()->findOneById($request->getParameter("id"));
    $this->forward404Unless($domain);
    $user = $this->session->getUser();

    if($domain->isSubscriber($user))
    {
      $domain->unSubscribe($user);
    }
    else
    {
      $domain->subscribe($user);
    }
    return $this->redirectBack();
  }
}
