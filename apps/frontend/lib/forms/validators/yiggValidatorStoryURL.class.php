<?php

/**
 * yiggValidatorStorieUrl validates an external url for stories to be valid and online
 *
 * @package    yigg
 * @subpackage helper
 */
class yiggValidatorStoryURL extends yiggValidatorURL
{
  /**
   * Configures the current validator.
   *
   * @see sfValidator
   */
  protected function configure($options = array(), $messages = array())
  {
    parent::configure($options, $messages);

    $this->addMessage('required',     "Bitte gib eine URL ein!");
    $this->addMessage('invalid_url',  "Wir konnten diese Adresse nicht kontaktieren.");
    $this->addMessage('known_url',    "Es gibt bereits eine Nachricht für diese URL.");
    $this->addMessage('blacklisted', "Diese Seite verwendet unzulässige Begriffe.");
    $this->addMessage('domain_blocked', "Diese Domain wurde gesperrt, weil vermehrt gegen unsere Nutzungsbedingungen verstoßen wurde.");
    $this->setOption('required', true);
    $this->addOption('unique',   true);
    $this->addOption('checkDNS', true);
    $this->addOption('appendHTTP', true);
    $this->addOption('story', false);
  }

  /**
   * check for uniqueness in story table
   *
   * @param   $value                string
   * @return    string    $value
   * @throws    sfValidatorError
   */
  function checkUnique($value)
  {
    $query = Doctrine_Query::create();
    $query->from("Story")
      ->where('external_url = ?', $value)
      ->addWhere('deleted_at IS NULL');

    // ignore current story if set
    $story = $this->getOption('story');
    if($story && $story->id)
    {
      $query->addWhere('id != ?', $story->get('id') );
    }

    $story = $query->fetchOne();
    if( !empty($story))
    {
      $controller = sfContext::getInstance()->getController();
      $link = $controller->genUrl( $story->getLink() );
      $this->setMessage('known_url', "Zu dieser bereits auf YiGG  <a href=\"" . $link ."\" title=\"Betrachte die Nachricht.\"> vorhandenen Nachricht </a>");
      throw new sfValidatorError($this, 'known_url');
    }

    if( StoryTable::isDeleted($value) )
    {
      $controller = sfContext::getInstance()->getController();
      $this->setMessage('known_url', "Diese Story gab es schon. Sie ist als gelöscht markiert und kann nicht hinzugefügt werden.");
      throw new sfValidatorError($this, 'known_url');
    }

    $session = sfContext::getInstance()->getUser();
    return $value;
  }

  /**
   * Checks if the content has blacklisted words
   *
   * @param String $url
   * @return boolean
   */
  function checkBlacklisted($url) {
    // ignore whitelisted
    if (Doctrine::getTable("Domain")->hasStatus($url, "whitelist")) {
      return true;
    }

    $body = yiggUrlTools::do_get($url);

    // remove html tags
    $body = strip_tags($body);

    // get blacklist
    $blacklist = Doctrine::getTable("Blacklist")->getBlacklistAsArray();
    $blacklist = implode("|", $blacklist);

    if (preg_match("/($blacklist)/i", $body)) {
      // get host of url
      $host = parse_url($url, PHP_URL_HOST);
      // get domain by hostname
      $domain = DomainTable::getInstance()->findOneByHostname($host);
      // check if domain exists
      if (!$domain) {
        // add a new if not
        $domain = new Domain();
        $domain->hostname = $host;
      }
      // block domain
      $domain->domain_status = "blacklisted";
      $domain->save();

      throw new sfValidatorError($this, 'blacklisted');
    }

    return true;
  }

  /**
   * Ensures the domain isn't blocked.
   *
   * @param String $url
   * @return boolean
   */
  function checkBlocked($url)
  {
    $is_blocked = true === Doctrine::getTable("Domain")->hasStatus($url, "blacklisted");
    if ($is_blocked) {
      throw new sfValidatorError($this, 'domain_blocked');
    }
    return $is_blocked;
  }
}