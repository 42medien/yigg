<?php

/**
 * create a validator for usernames
 */
class yiggValidatorUsername extends sfValidatorString
{
  private $invalid_usernames = array(
    'anonymous',
    'guest',
    'username',
    'administrator',
    'admin',
    'user',
    'permission',
    'marvin',
    'yigg',
    'mein-yigg',
    'yigg-team',
    'einstellungen',
    'adsense',
    'bearbeiten',
    'erfolge',
    'nachrichten',
    'willkommen',
    'register'
  );

    /**
     * Configures the current validator.
     *
     * @see sfValidator
      */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('invalid_name',   'Ungültiger Nutzername.');
    $this->addMessage('user_disabled',  'Nutzerkonto gelöscht.');
    $this->addMessage('bad_characters', 'Ungültiger Nutzername.');
    $this->addMessage('username_taken', 'Nutzername bereits vergeben.');

    $this->addOption('invalid_usernames', $this->invalid_usernames );
    $this->addOption('unique', true);
    $this->addOption('max_length', 32);

    parent::configure($options, $messages);
  }

  /**
   * check username for valid format and uniqueness
   *
   * @param  string  $value
   */
  protected function doClean($value)
  {
    parent::doClean($value);

    // Username only alowed characters 0 - 9 a - z and underscores.
    if( false == preg_match("/^[0-9a-zA-Z_.-]+$/", $value) )
    {
      throw new sfValidatorError($this, 'bad_characters');
    }

    // Check the username (the user is not logged in or present at this stage.)
    $username = yiggTools::stringToUsername($value);
    if( true == in_array($username, $this->invalid_usernames) )
    {
      throw new sfValidatorError($this, 'invalid_name');
    }

    if( true === UserTable::isDeleted($username) )
    {
      throw new sfValidatorError($this,'user_disabled');
    }

    $user = UserTable::getTable()->findOneByUsername($username);
    if( true == $this->getOption('unique') && false !== $user)
    {
      throw new sfValidatorError($this, 'username_taken');
    }
    return $username;
  }
}