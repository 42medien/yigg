<?php

/**
 *
 * @package     yigg
 * @subpackage  email
  */
class EmailLookup extends BaseEmailLookup
{
  public function preValidate($event)
  {
    $request = sfContext::getInstance()->getRequest();
    $this->ip_address = false === empty($this->ip_address)  ? $this->ip_address : $request->getRemoteAddress();
  }
}