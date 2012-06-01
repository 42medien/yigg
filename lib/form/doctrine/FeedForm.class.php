<?php

/**
 * Feed form.
 *
 * @package    yigg
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FeedForm extends BaseFeedForm
{
  public function configure()
  {
    parent::configure();
    $this->setWidget('user_id', new sfWidgetFormInput());
    $this->setWidget('url', new sfWidgetFormInput());
    $this->setWidget('error', new yiggWidgetReadonly(array('value'=> $this->getObject()->error)));
    $this->setWidget('load_time', new yiggWidgetReadonly(array('value'=> $this->getObject()->load_time)));
    $this->setWidget('change_frequency', new yiggWidgetReadonly(array('value'=> $this->getObject()->change_frequency)));

    unset( $this->validatorSchema['error'], $this->validatorSchema['load_time'],$this->validatorSchema['change_frequency']);
  }
}
