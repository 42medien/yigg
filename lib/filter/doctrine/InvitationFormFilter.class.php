<?php

/**
 * Invitation filter form.
 *
 * @package    yigg
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class InvitationFormFilter extends BaseInvitationFormFilter
{
public function configure()
  {
    $this->removeFields();
  }

  protected function removeFields()
  {
    unset(
      $this['user_id'],$this['created_at'],$this['updated_at']
    );
  }
}
