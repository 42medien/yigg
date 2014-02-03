<?php

/**
 * story actions.
 *
 * @package    yigg
 * @subpackage story
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class storiesActions extends sfActions {

  public function preExecute() {
    parent::preExecute();

    $this->setLayout(false);

    $user = null;
    $server = new OAuthSymfonyServer();

    // verify the request
    try {
      //$req = OAuthRequest::from_request();
      //$result = $server->verify_request($req);

      // split result array
      //$consumer = $result[0];
      //$token = $result[1];

      // lookup the symfony consumer token to get the user-id
      //$auth_token = Doctrine::getTable("AuthToken")->lookup($consumer, 'access', $token->key);

      $user = Doctrine::getTable("User")->findOneById(1);
    } catch (OAuthException $e) {
      header('WWW-Authenticate: OAuth realm="http://api.yigg.de"');
      header('HTTP/1.0 401 Unauthorized');
      echo $e->getMessage();
      exit;
    }

    // send user auth
    if (!$user) {
      header('WWW-Authenticate: OAuth realm="http://api.yigg.de"');
      header('HTTP/1.0 401 Unauthorized');
      echo 'wrong auth';
      exit;
    }

    $this->user = $user;
  }

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeIndex(sfWebRequest $request) {
    $this->forward('default', 'module');
  }

  /**
   * Executes index action
   *
   * @param sfRequest $request A request object
   */
  public function executeNew(sfWebRequest $request) {
    if (!$request->isMethod("POST")) {
      return sfView::ERROR;
      exit;
    }

    $user = $this->user;

    // try to find matching stories in db
    $story = Doctrine_Query::create()->from("Story")
      ->where('external_url = ?', $request->getParameter("url"))
      ->addWhere('deleted_at IS NULL')
      ->fetchOne();

    // check if story was already posted
    if ($story) {
      $response = $story->rate($user);

      var_dump($response);
    }

    $post = $request->getPostParameters();

    var_dump($post);
  }
}
