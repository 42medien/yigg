<?php

class FormUserSettings extends yiggForm
{

  public function setup()
  {
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'privacy'  => new sfWidgetFormInputCheckbox(
            array(),
            array(
              'id'    => 'privacy',
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
          'privacy'       => new sfValidatorBoolean(),
        )
      )
    );
       // setup base parameters
    parent::setup();

    $this->widgetSchema->setNameFormat('editSettings[%s]');

    // set labels
    $this->widgetSchema->setLabels(
      array(
        'privacy'        => 'Nur Freunde dürfen mein Profil sehen.',
      )
    );
  }
}