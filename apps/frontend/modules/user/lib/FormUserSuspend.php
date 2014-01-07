<?php

/** 
 * This is a simple delete form
 * 
 * @package   yigg
 * @subpackage  user
 */
class FormUserSuspend extends yiggForm
{

  public function setup()
  {
    // form inputs
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'bbcode'   => new sfWidgetFormTextarea(),
        )
      )
    );

    // validators
    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
       'bbcode' => new sfValidatorString(
         array(
           'min_length' => 2,
           'max_length' => 2000
         ),
         array(
           'required'  => 'Bitte gib einen Grund für die Sperrung an',
           'min_length'=> 'Diese Beschreibung ist zu kurz.',
           'max_length'=> 'Die Beschreibung ist zu lang'
         )
       ),
      )
    )
  );

  // setup base parameters
  parent::setup();

  $this->widgetSchema->setNameFormat('DeleteForm[%s]');

  // set labels
  $this->widgetSchema->setLabels(
    array(
        'bbcode'      => 'Mail an User:',
      )
    );
  }
}