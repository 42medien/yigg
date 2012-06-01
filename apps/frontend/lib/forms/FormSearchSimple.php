<?php

/**
 * create a simple search form
 *
 * @package     yigg
 * @subpackage  story
 */
class FormSearchSimple extends yiggForm
{

  /**
   * setup form fields and validators
   */
  public function setup()
  {
    // form inputs
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'q'   => new sfWidgetFormInput(
          array(),
          array(
            'class'     => 'ninjaRequired',
          )
        ),
       )
      )
    );

    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
          'q' => new yiggValidatorPlainText(
            array(),
            array(
              'required'  => 'Search word is required'
            )
          )
        )
      )
    );

    parent::setup();
    $this->widgetSchema->setNameFormat('%s');
    $this->widgetSchema->setLabels(
      array(
        'q' => 'Suchen nach:',
      )
    );
  }
}
