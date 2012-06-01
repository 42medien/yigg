<?php


/**
 * form for story comments
 *
 * @package   yigg
 * @subpackage  story
 */
class FormStoryRate extends yiggForm
{

  /**
   * setup form fields and validators
   */
  public function setup()
  {
    parent::setup();

    // form inputs
    $this->setWidgetSchema(
      new sfWidgetFormSchema( array( 'ratingtoken'   => new sfWidgetFormInputHidden( array(), array("id" => "ratingtoken") ) ))
    );

    $this->setValidatorSchema(
      new sfValidatorSchema( array( 'ratingtoken'   => new sfValidatorPass() ))
    );
    $this->widgetSchema->setNameFormat('StoryRate[%s]');
  }

}