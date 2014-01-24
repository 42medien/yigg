<?php
/**
 * xmlrpc actions.
 *
 * @package    communipedia
 * @subpackage xmlrpc
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 2692 2006-11-15 21:03:55Z fabien $
 */
class oauth1Actions extends sfActions {

  /**
   * OAuth endpoint to request a token
   */
  public function executeRequest($request) {
    $server = new OAuthSymfonyServer();

    try {
      $req = OAuthRequest::from_request();
      $this->logMessage(print_r($req, true), 'debug');
      $token = $server->fetch_request_token($req);
      return $this->renderText($token);
    } catch (OAuthException $e) {
      $this->logMessage($e->getMessage(), 'err');
      $this->getResponse()->setContentType('text/plain');
      $this->getResponse()->setStatusCode(400);

      return $this->renderText($e->getMessage());
    }
  }

  /**
   * authorize to approve an oauth request token
   */
  public function executeAuthorize($request) {
    $server = new OAuthSymfonyServer();
    $store = new OAuthSymfonyStore();

    $oauth_request = OAuthRequest::from_request();

    // write request to session
    if (count($oauth_request->get_parameters()) > 0) {
      $server->storeRequestToSession( $oauth_request );
    }

    var_dump($this->getUser()->isAuthenticated());exit;

    // check user session
    if (!$this->getUser()->getUser()) {
      $this->getUser()->setRedirectAfterNextLogin('api/authorize');
      $this->redirect('http://yigg.local/login');
    }

        // check if it is a valid OAuth authorisation request
    if ($server->loadRequestFromSession()) {
      $oauth_request = $server->loadRequestFromSession();
      $request_token = OAuthConsumerTokenPeer::find(null, 'request', $oauth_request->get_parameter('oauth_token'));
      $consumer_key = $store->lookup_token(null, 'request', $oauth_request->get_parameter('oauth_token'));

      // if there is no valid consumer key...
      if (!$consumer_key || $request_token->isAuthorized()) {
        // delete session ...
        $server->storeRequestToSession();
        // and return error.
        $this->getResponse()->setContentType('text/plain');
        $this->getResponse()->setStatusCode(401);
        return $this->renderText('Invalid OAuth Token');
      // check if callback-url has the right domain
      }

      // get hosts and schemes
      $callback_host = UrlUtils::getHost($oauth_request->get_parameter('oauth_callback'));
      $callback_scheme = parse_url($oauth_request->get_parameter('oauth_callback'), PHP_URL_SCHEME);
      $community_host = UrlUtils::getHost($request_token->getCommunity()->getUrl());

      $this->oauth = $oauth_request->get_parameter('oauth_token');
      $this->community = $request_token->getCommunity();
    } else { // if 'oauth_token' is empty
      // delete session ...
      $server->storeRequestToSession();
      // and return error
      $this->getResponse()->setContentType('text/plain');
      $this->getResponse()->setStatusCode(401);
      return $this->renderText('Invalid OAuth Token');
    }
  }

  /**
   * approve an oauth request token once or always
   */
  public function executeApprove_authorization($request) {
    $server = new OAuthSymfonyServer();
    $store = new OAuthSymfonyStore();
    $oauth_request = $server->loadRequestFromSession();

    try {
      $store->approve_token($oauth_request, $this->getUser()->getUserId());
    } catch (Exception $e) {
      $request = null;
      // delete session ...
      $server->storeRequestToSession();
      $this->getResponse()->setContentType('text/plain');
      $this->getResponse()->setStatusCode(400);
      return $this->renderText('Invalid operation');
    }

    // delete session ...
    $server->storeRequestToSession();

    // check if there are already some get-params
    if (preg_match("/\?/i", $oauth_request->get_parameter('oauth_callback'))) {
      $binder = "&";
    } else {
      $binder = "?";
    }

    $this->redirect($oauth_request->get_parameter('oauth_callback').$binder."oauth_token=".$oauth_request->get_parameter('oauth_token'));
  }

  /**
   * decline an oauth request token
   */
  public function executeDecline_authorization($request) {
    $server = new OAuthSymfonyServer();
    $store = new OAuthSymfonyStore();

    if ($oauth_request = $server->loadRequestFromSession()) {
      $store->decline_token($oauth_request);
    }

    // delete session ...
    $server->storeRequestToSession();
  }

  /**
   * request an oauth access key
   */
  public function executeAccess($request) {
    $server = new OAuthSymfonyServer();

    try {
      $req = OAuthRequest::from_request();
      $token = $server->fetch_access_token($req);
      return $this->renderText($token);
    } catch (OAuthException $e) {
      $this->getResponse()->setContentType('text/plain');
      $this->getResponse()->setStatusCode(400);
      return $this->renderText($e->getMessage());
    }
  }
}