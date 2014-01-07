<?php 
class redirectorActions extends sfActions
{
  public function executeRedirector(sfWebRequest $request)
  {
    $key = $request->getParameter("key");
  	$redirect = RedirectTable::getTable()->findOneByRedirectKey($key);
    $this->forward404If($redirect === false);
    
    RedirectLog::log($request, $redirect);
    return $this->redirect($redirect->url, 301);
  }  
}
?>