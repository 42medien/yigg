<?php

/**
 * yiggValidatorUrl validates a url to be valid and online
 *
 * @package    yigg
 * @subpackage helper
 */
class yiggValidatorURL extends sfValidatorUrl
{
 /**
  * Configures the current validator.
  *
  * @see sfValidator
  */
  protected function configure($options = array(), $messages = array())
  {
    $options = array_merge($options, array(
      "protocols" => array("http", "https")
    ));

    $messages = array_merge($messages, array(
      "invalid" => "Das Format der URL ist nicht zulässig."
    ));

    parent::configure($options, $messages);

    $this->addMessage('required', "A URL is required");
    $this->addMessage('invalid_url', "We couldn't contact the address you added");
    $this->addMessage('known_url',  "Sorry, we already have a story for this link");
    $this->setOption('required', true);
    $this->addOption('unique',   true);
    $this->addOption('checkDNS', true);
    $this->addOption('globalBlacklist', true);
    $this->addOption('story', false);
    $this->addOption('max_length', 254);
  }

  /**
   * check url for being well formed and valid and not inside the system
   *
    * @see sfValidator
    */
  protected function doClean($value)
  {

    $value = parent::doClean($value);
    
    //remove all new lines and spaces.
    $value = preg_replace("{\n|\s}is","",$value);

    // clean value
    $value = (string) $value;
    $value = yiggUrlTools::encodeUmlauts($value);
    
    $value = parent::doClean($value); //We have to clean here - url its expanded and encoded (maybe longer)

    // check if hostname is blocked
    if( true === $this->getOption('globalBlacklist'))
    {
      $this->checkBlocked( $value );
    }

    // check if url is unique (with option unique => default)
    if( true === $this->getOption('unique') )
    {
      $this->checkUnique($value);
    }
    return $value;
  }

  /**
   * check for uniqueness of url, must be separately done in each validator type
   * (e.g. story, rss, homepage etc.)
   *
   * @param $value Object
   */
  public function checkUnique($value)
  {
  }

  /**
   * Checks if the url's hostname is block
   *
   * @param $value Object
   */
  public function checkBlocked($value)
  {
  }

 /**
  * Returns boolean if the url is found or not based on the response from the
  * nameservers. Checks for an A record ( all websites will follow these.)
  *
  * @param string url the url to check with the host.
  * @return boolean true if the URL is contactable and avaliable.
  */
  private function isProperUrl($url)
  {
    //we check to see if there is a http:// at the beggining otherwise the dns check goes astray.
    preg_match('^((ht|f)tp(s?)\:\/\/|~/|/)^',$url,$matches);
    $url_info = parse_url($url);
    if(count($matches) > 0 && $url_info)
    {
      if( array_key_exists( 'host', $url_info) && checkdnsrr( $url_info['host'] . '.', 'A' ) )
      {
        return true;
      }
    }
    else
    {
      if( array_key_exists( 'path', $url_info) && checkdnsrr( $url_info['path'] . '.', 'A' ) )
      {
        return true;
      }
    }
    return false;
  }
}