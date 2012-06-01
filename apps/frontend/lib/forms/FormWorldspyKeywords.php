<?php
class FormWorldspyKeywords extends yiggForm
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
          'keywords'   => new sfWidgetFormInput(
            array(),
            array(
              'class'     => 'ninjaOptional',
              'id'		=> 'keywords',
            )
          ),
        )
      )
    );
    $this->getWidgetSchema()->setHelps(array("keywords"=> "Tags (mit Kommata getrennt)"));

    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
          'keywords' => new yiggValidatorTag(),
        )
      )
    );

    // setup base
    parent::setup();

    // set labels
    $this->widgetSchema->setLabels(
      array(
        'keywords' => 'Filtern nach:',
      )
    );
  }
}

?>
