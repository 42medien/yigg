<?php
class FormReportBug extends yiggForm
{
  public function setup()
  {
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'error_action'   => new sfWidgetFormTextarea(),
          'error'   => new sfWidgetFormTextarea()
        )
      )
    );

    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
          'error_action' => new yiggValidatorPlainText(),
          'error'=> new yiggValidatorPlainText(),
        )
      )
    );

    parent::setup();

    $this->widgetSchema->setLabels(
      array(
        'error_action' => 'Was hast Du gemacht?',
        'error' => 'Was ist passiert?'
      )
    );
  }
}
?>