<?php

/**
 * create a validator for usernames which does not have an error if the user exists in the system
 */
class yiggValidatorYiggUser extends yiggValidatorUsername
{
 /**
  * Configures the current validator.
  *
  * @see sfValidator
  */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
    $this->addMessage('no_user',  'Nutzername nicht vorhanden.');
  }

  /**
   * Check username for valid format (using parent) and make sure the user exists, otherwise throw an exeption.
   * Used in PM system.
   *
   * @param  string  $value
   */
  protected function doClean($value)
  {
    $value = parent::doClean($value);

    // check for all user from db (even deleted)
    $user = Doctrine::getTable("User")->findOneByUsername(yiggTools::stringToUsername($value));
    if( false === $user )
    {
      throw new sfValidatorError($this, 'no_user');
    }
    return $user;
  }
}