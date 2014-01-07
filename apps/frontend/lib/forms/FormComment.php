<?php
class FormComment extends yiggForm
{

  public function setup()
  {
    parent::setup();

    // form inputs
    $this->setWidgets(
      array(
        'description' =>  new sfWidgetFormTextarea(),
        'name' => new sfWidgetFormInput(),
        'email' => new sfWidgetFormInput(),
      )
    );

    $this->setValidators(array(
      'description' => new yiggValidatorPlainText(
         array('min_length'  => 4, 'max_length'  => 4000),
         array(
           'required'    => 'Bitte einen Kommentartext einfügen',
           'min_length'  => 'Dieser Kommentar war zu kurz.',
           'max_length'  => 'Dein Kommentar ist zu lang. Bitte kürze ihn auf unter %max_length% Zeichen.'
       )),
      'email' => new sfValidatorEmail(),
      'name' => new yiggValidatorPlainText()
    ));

    $this->widgetSchema->setLabels(
      array(
        'name' => 'Name',
        'email' => "E-Mail (wird nicht veröffentlicht)",
        'description' => 'Kommentar',
      )
    );

    $this->getWidgetSchema()->setNameFormat(
      "Comment[%s]"
    );
  }
}