<?php

/**
 * This allows us to use persistant logins
 *
 * @package   yigg
 * @subpackage   user
 */
class rememberFilter extends sfFilter
{
  public function execute ( $filterChain )
  {
    // execute this filter only once
    if( true === $this->isFirstCall() )
    {
      // Get our session and make sure this isn't called if they are authenticated.
      $userSession = $this->getContext()->getUser();

      // check for the cookie if they are not authenticated, or the session is timed out.
      if( true === $userSession->hasUser() )
      {
        return $filterChain->execute();
      }

      $cookie = $this->getContext()->getRequest()->getCookie('YGID',false);
      if( false === $cookie )
      {
        $filterChain->execute();
      }

      // grab the key from the remember key.
      $value = unserialize( base64_decode($cookie) );
      if( false === is_array( $value ) )
      {
        return $filterChain->execute();
      }

      // Check the key, Username, and IP is the same.
      // first position is our rememberkey.
      $key = RememberKeyTable::getTable()->findOneByRememberKey( $value[0] );
      if( false !== $key && $key->user_id !== 0 )
      {
        $user = $key->User;
        if( $user instanceof User && $user->id > 0 && $user->status ==1 )
        {
          $userSession->login($user);
        }
      }
      // execute next filter
    }

    return $filterChain->execute();
  }
}
