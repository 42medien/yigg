<?php

/**
 * story actions.
 *
 * @package    yigg
 * @subpackage story
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class profilesActions extends sfActions {

  public function preExecute() {
    parent::preExecute();

    $user = null;
    $server = new OAuthSymfonyServer();

    // verify the request
    try {
      $req = OAuthRequest::from_request();
      $result = $server->verify_request($req);

      // split result array
      $consumer = $result[0];
      $token = $result[1];

      // lookup the symfony consumer token to get the user-id
      $auth_token = Doctrine::getTable("AuthToken")->lookup($consumer, 'access', $token->key);

      $user = Doctrine::getTable("User")->findOneById($auth_token->getUserId());
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

  public function executeMe() {
    $this->setLayout(false);

    $user = $this->user;

    $profile = new stdClass();
    $profile->id = $user->getId();
    $profile->displayName = $profile->preferredUsername = $profile->nickname = $user->getUsername();
    if ($about_me = $user->getConfig()->get('about_me', null, 'profile')) {
      $profile->note = $about_me;
    }
    if ($birthday = $user->getConfig()->get('birthday', null, 'profile')) {
      $profile->birthday = date("Y-m-d", strtotime($birthday));
    }
    if ($sex = $user->getConfig()->get('sex', null, 'profile')) {
      $profile->gender = $sex;
    }
    $profile->emails[] = array("value" => $user->getEmail());
    if ($website = $user->getConfig()->get('website', null, 'profile')) {
      $profile->urls[] = array("value" => $website, "type" => "blog");
    }
    $profile->urls[] = array("value" => "http://yigg.de/profil/{$user->getUsername()}", "type" => "profile");
    //if ($user->hasAvatar()) {
    //  $profile->photos[] = array("value" => $user->getAvatar(), "type" => "avatar");
    //}
    $this->profile = $profile;
  }
}
