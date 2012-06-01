<?php
/**
 * Class yiggTwitterOauth
 * Offers a layer of indirection between our system and the acutal API-Library
 */
class yiggTwitterOauth
{
  private $twitter;
  private $username;
  
  public function __construct($oauthToken = null, $oauthSecret = null)
  {
    $this->twitter = new EpiTwitter(sfConfig::get('app_twitter_consumerKey'), sfConfig::get('app_twitter_consumerSecret'), $oauthToken, $oauthSecret);
  }
  
  /**
   * Get Username for the current user
   * @return string
   */
  public function getClientUsername()
  {
    if(empty($this->username))
    {
      $result = $this->executeRequest("get_accountVerify_credentials");
      $this->username = $result["screen_name"];   	
    }
    return $this->username;    
  }
  
  /**
   * Verifys the tokens
   * @return boolean Are tokens valid? 
   */
  public function verifyTokens()
  {
    $result = $this->executeRequest("get_accountVerify_credentials");    
    return isset($result) && is_array($result);
  }

  /**
   * Returns access tokens for callbacks
   * @param string $oauth_token
   * @return array
   */
  public function getAccessToken($oauth_token)
  {
    $this->twitter->setToken($oauth_token);
    return $this->twitter->getAccessToken();
  }

  /**
   * Returns an url that the user can use to assign his account with twitter
   * @return string
   */
  public function getAuthenticationUrl()
  {
    return $this->twitter->getAuthenticateUrl();
  }
  
  /**
   * Sets a new status (ordinary twitter message) 
   *
   * @param string $message (not longer than 140 chars!)
   * @return array decoded json response
   */
  public function updateStatus($message)
  {
    $params = array("status" => substr($message,0,140), "source" => sfConfig::get('app_twitter_via'));
    $result = $this->executeRequest("post_statusesUpdate", $params);
    return $result;
  }
  

  public function getTimeline()
  {
    return $this->executeRequest("get_statusesUser_timeline", $params);
  }
  
  public function getStatusesMentions($params)
  {
    return $this->executeRequest("get_statusesMentions", $params);
  }
  
  public function getDirectMessages($params=null)
  {
    return $this->executeRequest("get_direct_messages", $params);
  }
  
  /**
   * Used to call the underlying 
   *
   * @param string $method
   * @param array $params
   * @return array decoded json response
   */
  private function executeRequest($method, $params=null)
  {
      $result = $this->twitter->$method($params);
      return $result->response;    
  }
  
  /**
   * Get Followers
   */
  public function getFollowersIds($username=null)
  {
    if(empty($username))
    {
      $username = $this->getClientUsername();
    }
    return $this->executeRequest("get_followersIds", array("screen_name" => $username));
  }
  
  /**
   * Get Followers
   */
  public function getFriendsIds($username=null)
  {
    if(empty($username))
    {
      $username = $this->getClientUsername();
    }
    return $this->executeRequest("get_friendsIds", array("screen_name" => $username));
  }
  
  /**
   * Get Followers that are not your friends already
   *
   * @param string $username
   */
  
  public function getFollowersIdsWithoutFriends($username=null)
  {
    if(empty($username))
    {
      $username = $this->getClientUsername();
    }
    $followers = $this->getFollowersIds($username);
    $friends = $this->getFriendsIds($username);
    return array_diff($followers, $friends);
  }
  
  /**
   * Get Statuses for all followers
   */
  public function getStatusesFollowers($username=null)
  {
    if(empty($username))
    {
      $username = $this->getClientUsername();
    }
    return $this->executeRequest("get_statusesFollowers", array("screen_name" => $username));    
  }
  
  public function search($term, $params = array())
  {
    $response = $this->executeRequest("get_search", array_merge(array("q" => $term), $params));
    return $response["results"];    
  }
  
  /**
   * Get informations about a certain user
   */
  public function getUserInfo($user_id)
  {
    return $this->executeRequest("get_usersShow", array("id" => $user_id));
  }
  
  /**
   * Get Statuses of all followers that are not friends
   * @param string username
   */
  public function getStatusesFollowersWithoutFriends($username=null)
  {
    if(empty($username))
    {
      $username = $this->getClientUsername();
    }
    
    $friends = $this->getFriendsIds($username);
    $statuses = $this->getStatusesFollowers($username);
        
    foreach($statuses as $key => $status)
    {
      if(in_array($status["id"], $friends))
      {
        unset($statuses[$key]);
      }
    }
    return $statuses;
  }
  
  /**
   * Follows a user with this user-account
   * @param String $username 
   */
  public function followUser($username)
  {
    return $this->executeRequest("post_friendshipsCreate", array("screen_name" => $username));
  }
  
  /**
   * Send a direct message
   * @param String recipient
   */
  public function sendDirectMessage($username, $message)
  {
  	return $this->executeRequest("post_direct_messagesNew", array("screen_name" => $username, "text" => $message));
  }
  
  /**
   * Checks friendship between 2 users
   * @param string Friend
   * @param string User
   * 
   * @return boolean (true if $potential_follower follows $username)
   */
  public function isFollowing($potential_follower, $username=null)
  {
  	if(empty($username))
  	{
  	  $username = $this->getClientUsername();
  	}
  	return (bool)$this->executeRequest("get_friendshipsExists", array("user_a" => $potential_follower, "user_b" => $username));
  }
}
?>