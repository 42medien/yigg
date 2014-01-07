<?php
class FormPostboxSimpleCreate extends yiggForm
{

  public function setup()
  {
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'message'   => new sfWidgetFormTextarea()
        )
      )
    );

    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
          'message'   => new yiggValidatorCompactPmText(
            array(
              'min_length' => 3,
              'max_length' => 3000,
            ),
            array(
              'required'  => 'Bitte Text eingeben.',
              'min_length'=> 'Bitte Text eingeben',
              'max_length'=> 'Bitte max. 3000 Zeichen verwenden. ',
            )
          ),
        )
      )
    );

    parent::setup();

    $this->widgetSchema->setNameFormat('PostboxCreateMessage[%s]');

    $this->widgetSchema->setLabels(
      array(
        'message'       => 'Mitteilung:',
      )
    );
  }
}
?>