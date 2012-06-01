<?php

/**
 * Validator for every kind of plainText-fields. Like titles and name.
 */
class yiggValidatorPlainText extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
  }

  protected function doClean($value)
  {
    $value = strip_tags($value);
    $value = preg_replace("{(\&nbsp\;)}is"," ",$value);

    $value = trim($value);
    $value = htmlspecialchars($value, null, "UTF-8");

    return parent::doClean($value);
  }
}