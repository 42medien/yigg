<?php
class Redirect extends BaseRedirect
{
  /**
   * generates the redirect key on validation.
   *
   * @see lib/1.3/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine/Doctrine_Record#preValidate($event)
   */
  public function preValidate($event)
  {
    $this->redirect_key = self::createKey();
  }

  public static function createOrGetIfExists($url)
  {
    $redirect = RedirectTable::getByUrl($url);
    if(false !== $redirect)
    {
      return $redirect;
    }
    return self::create($url);
  }

  public static function create($url)
  {
  	$redirect = new Redirect();
  	$redirect->url = $url;
  	$redirect->save();
  	return $redirect;
  }

  public static function createKey()
  {
  	do
  	{
      $key = randTools::getRandId(5);
  	  $redirect = RedirectTable::getTable()->findOneByRedirectKey($key);
  	}
  	while($redirect !== false);
  	return $key;
  }

  public function getMiniUri()
  {
  	return "http://yigg.it/".$this->redirect_key;
  }

  public function getRetweetLink()
  {
  	$tweet_lenght = 140;
  	$prefix = "RT @mugim: ";
  	$title_lenght =  $tweet_lenght - (strlen($this->getMiniUri() + 1) - strlen($prefix));
  	$tweet = $prefix . substr($this->title,0,$title_lenght) . " " . $this->getMiniUri();
  	return sprintf("http://twitter.com/home/?status=%s", urlencode($tweet));
  }
}