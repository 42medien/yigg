<?php

class FormURL extends sfForm
{
  public function setup()
  {
  	parent::setup();
  	
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
              'url'   => new sfWidgetFormInput(),
             )          
        )
      );

      // validators
      $this->setValidatorSchema(
        new sfValidatorSchema(
          array(
            'url' => new sfValidatorUrl(),
          )
         )
       );  

    $this->widgetSchema->setLabels(
      array(
          'url'  => 'URL:',
      )
    );
    
    $this->offsetUnset("_csrf_token");
  }
}