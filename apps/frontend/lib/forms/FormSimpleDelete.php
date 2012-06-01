<?php

/** 
 * This is a simple delete form
 * 
 * @package   yigg
 * @subpackage  user
 */
class FormSimpleDelete extends yiggForm
{

  public function setup()
  {
    // form inputs
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'confirm' => new sfWidgetFormInputCheckbox(
            array(),
            array(
              'id'  => 'confirm',
              'class' => 'chkBox'
            )
          ),
        )
      )
    );

    // validators
    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
       'confirm' => new sfValidatorChoice(
          array(
            'choices' => array('true', 'on', 1)
          ),
          array(
           'invalid'  => 'Zum löschen dieses Feld klicken:',
           'required' => 'Zum löschen dieses Feld klicken:'
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
        'confirm'     => 'Wirklich löschen?:',
      )
    );
  }
}