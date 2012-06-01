<?php

/**
 * This allows us to use persistant logins
 *
 * @package   yigg
 * @subpackage   user
 */
class securityFilter extends sfFilter
{
  public function execute ($filterChain)
  {
    // execute this filter only once
    if( $this->isFirstCall() )
    {
      // Get our session and make sure this isn't called if they are authenticated.
      $request = $this->getContext()->getRequest();

      // check for tokens if request is a XMLhttpRequest . (tokens are checked at form level)
      if( false === $request->isXmlHttpRequest() ||  (true === $request->isXmlHttpRequest() && $request->hasSecurityToken()) )
      {
        // execute next filter
        $filterChain->execute();
      }
      else
      {
        // Send a 403 Forbidden response. (AJAX Request sent without appropriate token)
        $response = $this->getContext()->getResponse();
        $response->setStatusCode(403);
        return $response;
      }
    }
    else
    {
      $filterChain->execute();
    }
  }
}
