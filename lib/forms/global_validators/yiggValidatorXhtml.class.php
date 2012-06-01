<?php

/**
 * Validator for the text of our stories
 */

class yiggValidatorXhtml extends sfValidatorString
{
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);
  }

  protected function doClean($value)
  {
    // strip all non breaking spaces.
    $value = preg_replace("{(\&nbsp\;)}is"," ",$value);
   
    $cleaner = new yiggHtmlCleaner();
    $value = $cleaner->clean($value);

    $value = yiggTools::tidyXhmtl($value);
    $value = parent::doClean($value);

    return $value;
  }
}