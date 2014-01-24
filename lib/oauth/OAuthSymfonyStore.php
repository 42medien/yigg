<?php
/**
 * an OAuth store for symfony
 *
 * @author Matthias Pfefferle
 */
class OAuthSymfonyStore extends OAuthDataStore {

  /**
   * looks up a consumer using a consumer_key
   *
   * @param string $consumer_key
   * @return OAuthConsumer | null
   */
  public function lookup_consumer($consumer_key) {
    $data = $this->get_consumer_data($consumer_key);

    if (!empty($data)) {
      return new OAuthConsumer($data->getCommunity(), $data->getApiKey());
    }

    return null;
  }

  /**
   * looks up a nonce
   *
   * @param OAuthConsumer $consumer
   * @param OAuthToken $token
   * @param string $nonce
   * @param time $timestamp
   * @return string | null
   */
  public function lookup_nonce($consumer, $token, $nonce, $timestamp) {
    Doctrine::getTable("AuthNonce")->deleteExpired();

    if (!Doctrine::getTable("AuthNonce")->hasBeenUsed($consumer, $token, $nonce)) {
      $auth_none = new AuthNonce();
      $auth_none->setConsumerKey( $consumer->key );
      $auth_none->setTokenKey( $token->key );
      $auth_none->setNonce( $nonce );
      $auth_none->save();

      return null;
    }

    return $nonce;
  }

  /**
   * looks up a token using
   *
   * @param OAuthConsumer $consumer
   * @param string $token_type for example 'request' or 'access'
   * @param string $token
   *
   * @return OAuthToken | null
   */
  public function lookup_token($consumer, $token_type, $token) {
    $auth_token = Doctrine::getTable("AuthToken")->find($consumer, $token_type, $token);

    if (!empty($auth_token)) {
      return new OAuthToken($auth_token->getTokenKey(), $auth_token->getTokenSecret());
    }

    return null;
  }

  /**
   * get a new access token
   *
   * @param OAuthToken $token
   * @param OAuthConsumer $consumer
   *
   * @return OAuthToken | null
   */
  public function new_access_token($token, $consumer, $callback = null) {
    $oauth_consumer = $this->get_consumer_data($consumer->key);
    $auth_token = Doctrine::getTable("AuthToken")->find($consumer, 'request', $token->key);

    if (!empty($oauth_consumer) && $auth_token->isAuthorized()) {
      $access_token = $this->new_token($consumer->key, 'access', $auth_token->getUserId());
      $auth_token->delete();

      return $access_token;
    }

    return null;
  }

  /**
   * get a new request token
   *
   * @param OAuthConsumer $consumer
   * @return OAuthToken | null
   */
  public function new_request_token($consumer, $callback = null) {
    $oauth_consumer = $this->get_consumer_data($consumer->key);

    if (!empty($oauth_consumer)) {
      return $this->new_token($consumer->key, 'request');
    }

    return null;
  }

  /**
   * approve a request token
   *
   * @param OAuthRequest $pOAuthRequest
   * @param int $pUserId
   */
  public function approve_token(OAuthRequest $oauth_request, $pUserId) {
    $auth_token = Doctrine::getTable("AuthToken")->find(null, 'request', $oauth_request->get_parameter('oauth_token'));

    $auth_token->setUserId($pUserId);
    $auth_token->save();
  }

  /**
   * decline and delete a request token
   *
   * @param OAuthRequest $pOAuthRequest
   */
  public function decline_token(OAuthRequest $oauth_request) {
    $auth_token = Doctrine::getTable("AuthToken")->find(null, 'request', $oauth_request->get_parameter('oauth_token'));

    if ($auth_token) {
      $auth_token->delete();
    }
  }

  /**
   * get a consumer by the consumer-key
   *
   * @param string $consumer_key
   * @return OAuthConsumerRegistry | null
   */
  private function get_consumer_data($consumer_key) {
    //return CommunityPeer::getCommunityById($consumer_key, false);
    //return OAuthConsumerRegistryPeer::retrieveByConsumerKey($consumer_key);
  }

  /**
   * generate a new token
   *
   * @param string $consumer_id
   * @param string $token_type
   * @param int $user_id default is 'null'
   * @return OAuthToken
   */
  private function new_token($consumer_id, $token_type, $user_id = null) {

    $key = sha1($_SERVER['REMOTE_ADDR'] . microtime() . (string)rand());
    $secret = sha1(md5($_SERVER['REMOTE_ADDR']) . microtime() . (string)time());

    $token = new AuthToken();

    $token->setConsumerKey($consumer_id);
    $token->setTokenKey($key);
    $token->setTokenSecret($secret);
    $token->setTokenType($token_type);

    if ($user_id != null) {
      $token->setUserId($user_id);
    }

    $token->save();

    return new OAuthToken($key, $secret);
  }
}

?>
