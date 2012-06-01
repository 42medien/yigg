<?php
/**
 * Validates a twitterFriendship
 */
class yiggValidatorCompactPmText extends yiggValidatorPlainText
{
  /**
   * Configures the current validator.
   *
   * @see sfValidator
   */
  protected function configure($options = array(), $messages = array())
  {
    $this->addMessage('no_recipients', 'Du musst mindestens einen Empfänger angeben.');
    $this->addMessage('not_friend', 'Es sind nicht mehr als 5 Empfänger erlaubt.');

    parent::configure($options, $messages);
  }

  /**
   * check username for valid format and uniqueness
   *
   * @param  string  $value
   */
  protected function doClean($value)
  {
    $value = parent::doClean($value);
    
    $recipients = yiggTools::getAdressedUsers($value);

    if(count($recipients) === 0)
    {
        throw new sfValidatorError($this, 'no_recipients');
    }
    if(count($recipients) > 5)
   {
        throw new sfValidatorError($this, 'to_many_recipients');
    }
    return $value;
  }
}
?>