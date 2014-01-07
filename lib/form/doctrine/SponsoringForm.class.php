<?php

/**
 * Sponsoring form.
 *
 * @package    yigg
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class SponsoringForm extends BaseSponsoringForm
{
  public function configure()
  {
    $this->removeFields();
    parent::configure();

    $this->setWidget('expires', new yiggWidgetReadonly(array('value'=> $this->getObject()->created_at)));
    $this->setWidget('created_at', new yiggWidgetReadonly(array('value'=> $this->getObject()->created_at)));
    $this->setWidget('updated_at', new yiggWidgetReadonly(array('value'=> $this->getObject()->created_at)));
    unset($this->validatorSchema['created_at'],$this->validatorSchema['updated_at'], $this->validatorSchema['expires']);
  }

  protected function removeFields()
  {
    unset(
    $this['image_id'],
    $this['ratings_list']
    );
  }
}
