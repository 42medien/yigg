<?php

/**
 *
 * @package     yigg
 * @subpackage  story
  */
class Rating extends BaseRating
{
  public function preValidate($event)
  {
    $request = sfContext::getInstance()->getRequest();
    $this->ip_address = false === empty($this->ip_address)  ? $this->ip_address : $request->getRemoteAddress();
    $this->user_agent = $request->getHttpHeader("USER_AGENT") ? mb_substr($request->getHttpHeader("USER_AGENT"),0,255,"utf-8") : "Unknown";
  }
}