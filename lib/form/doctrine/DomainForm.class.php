<?php

/**
 * Domain form.
 *
 * @package    yigg
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DomainForm extends BaseDomainForm
{
  public function configure()
  {
    unset(
      //$this["hostname"],
      $this["created_at"],
      $this["updated_at"]
    );
  }
}
