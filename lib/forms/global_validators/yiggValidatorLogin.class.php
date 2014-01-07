<?php

/**
 * yiggValidatorLogin validates login credentials
 *
 * @package    yigg
 * @subpackage helper
 */
class yiggValidatorLogin extends sfValidatorSchema
{

  const MSG_LOGIN_ERROR    = 'login_error';
  const MSG_USER_DISABLED  = 'user_disabled';
  const MSG_USER_SUSPENDED  = 'user_suspended';
  const MSG_USER_DELETED   = 'user_deleted';


  private $errorSchema;
  private $clean;

  /**
   * setup configuration for login validator
   * possible error messages:
   *   - login_error    invalid user or password
   *   - user_disabled  user disabled
   *
   * @param array $options
   * @param array $messages
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage( self::MSG_LOGIN_ERROR,  'Login failed');
    $this->addMessage( self::MSG_USER_DISABLED,'User disabled');
    $this->addMessage( self::MSG_USER_SUSPENDED,'User suspended');
    $this->addMessage( self::MSG_USER_DELETED, 'User has been deleted');
  }

  /**
   * process validation
   *
   * @param   array   $values
   * @throws  sfValidatorErrorSchema    fields or login data invalid
   * @throws  InvalidArgumentException  if no array is passed
   * @return   array
   */
  public function doClean( $values )
  {
    if ( false == ( is_array($values) && count($values) > 0) )
    {
      throw new InvalidArgumentException('yiggValidatorLogin is expecting array');
    }

    $this->clean = array();
    $this->errorSchema  = new sfValidatorErrorSchema( $this );

    // validate given values
    foreach ($values as $name => $value)
    {
      // validate value
      try
      {
        if( false == isset( $this->fields[$name]) )
        {
          throw new InvalidArgumentException( 'Unknown field "' . $name . '" found in yiggLoginValidator.' );
        }
        $this->clean[$name] = $this->fields[$name]->clean($value);
      }
      catch (sfValidatorError $e)
      {
        $this->clean[$name] = null;
        $this->errorSchema->addError($e, (string) $name);
      }
    }

    // check login only with valid fields
    if( count( $this->errorSchema ) > 0 )
    {
      return $this->exitValidation();
    }

    try
    {
      if( false == ( isset($values['username']) && isset($values['password']) ) )
      {
        throw new InvalidArgumentException( 'Required fields are missing in yiggLoginValidator.' );
      }

      $username = $values['username'];
      $password = $values['password'];

      // anonymous / guest are not users (probably isnt even needed but could add a funny thing
      if ( $username != 'anonymous' && $username != "guest" )
      {
        // Create the DB Query
        $request  = sfContext::getInstance()->getRequest();
        $userSession = sfContext::getInstance()->getUser();
        $user = Doctrine::getTable("User")->findOneByUsername($username);

        // enforce wait for login...
        $failedLogins = max( ($user && $user->id ? $user->failed_logins : 0) , $userSession->getAttribute("failed_logins", 0, "FailedLogins") );

        //wait
        do
        {
          $timeout = $userSession->enforceWait( $failedLogins );
        }
        while( $timeout != 0 );

        if( false === $user || (int) $user->id === 0 )
        {
          if( true === UserTable::isDeleted( (string) $username) )
          {
            throw new sfValidatorError($this, self::MSG_USER_DELETED);
          }
          throw new sfValidatorError($this,self::MSG_LOGIN_ERROR);
        }

        if( intval( $user->status ) === 0 )
        {
          throw new sfValidatorError($this, self::MSG_USER_DISABLED);
        }
        
        if( intval( $user->block_post ) === 1 )
        {
          throw new sfValidatorError($this, self::MSG_USER_SUSPENDED);
        }

        if ( false === $user->checkPassword( $password ) )
        {
          throw new sfValidatorError($this, self::MSG_LOGIN_ERROR);
        }

        $user->loadReference("Permissions");
        $loginForm = $request->getParameter('LoginForm');
        $userSession->login( $user, array_key_exists("remember", $loginForm ) );
      }
    }
    catch(sfValidatorError $e)
    {
      // executeHandleLoginError()
      $userSession->setAuthenticated( false );
      ++$failedLogins;

      if( $user )
      {
        $user->failed_logins = $failedLogins;
        $user->save();
      }

      // save to session too.
      $userSession->setAttribute( "failed_logins", $failedLogins ,"FailedLogins");
      sfContext::getInstance()->getLogger()->log( 'yiggValidatorLogin::doClean called: User failed loggin - ' . $failedLogins . ' times! - IP:' . sfContext::getInstance()->getRequest()->getRemoteAddress() . "for user:". $username, sfLogger::NOTICE);
      $this->errorSchema->addError( $e, $this->getMessage( $e->getMessage()) );
    }
    return $this->exitValidation();
  }

  /**
   * exit current validation, check for validation erros
   *
   * @return array
   */
  private function exitValidation()
  {
    if ( count($this->errorSchema) )
    {
     throw $this->errorSchema;
    }
    return $this->clean;
  }
}