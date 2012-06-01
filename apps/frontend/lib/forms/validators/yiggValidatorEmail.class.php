<?php

class yiggValidatorEmail extends sfValidatorEmail
{

  public function configure( $options = array(), $messages = array() )
  {
    $this->addMessage('not_unique', 'Diese E-Mail Adresse wird schon benutzt.');
    $this->addMessage('no_mx_record', 'Die Domains kann keine E-Mail emfangen');
    $this->addMessage('timeout_interupt', 'Die E-Main Adresse konnte nicht überprüft werden');

    $this->addMessage('tmp_email', 'Temporäre E-Mail Adressen sind nicht erlaubt.');

    $this->addOption('unique',   false);
    parent::configure( $options, $messages );
  }

    /**
     * Check if a domain has a MX DNS-Record.
     * @param string Email
     * @return boolean true/false
     */
  function domain_exists( $email, $record = 'MX')
  {
    list($user,$domain) = explode('@',$email);
    return checkdnsrr($domain,$record);
  }

  public function doClean($value)
  {
    if ( false === strpos($value, '@') )
    {
      throw new sfValidatorError($this, 'invalid');
    }

    list($user,$domain) = explode('@',$value);

    $value = $user.'@'.$domain;

    // Validate the email using the PHP-Filter-Extension
    if( false === filter_var($value , FILTER_VALIDATE_EMAIL))
    {
      throw new sfValidatorError($this, 'invalid');
    }

    require_once(sfContext::getInstance()->getConfigCache()->checkConfig('config/invalid_email_domains.yml'));
    $invalid_domains = sfConfig::get("email_email_invalidDomains", array());

    if(false !== array_search($domain, $invalid_domains))
    {
      throw new sfValidatorError($this, 'tmp_email');
    }

    $value = parent::doClean($value);

    if( false == $this->domain_exists($value))
    {
      throw new sfValidatorError($this, 'no_mx_record');
    }

    if( false == $this->getOption('unique') )
    {
      return $value;
    }

    $user_id = Doctrine::getTable("User")->emailExists( $value );
    if(false === $user_id)
    {
      return $value;
    }

    $remote_address     = sfContext::getInstance()->getRequest()->getRemoteAddress();
    $lookups_by_this_ip = EmailLookupTable::getCountByIp( $remote_address );

    $emailLookup =  new EmailLookup();
    $emailLookup->user_id = $user_id;
    $emailLookup->save();

    throw new sfValidatorError($this, 'not_unique');
  }
}
