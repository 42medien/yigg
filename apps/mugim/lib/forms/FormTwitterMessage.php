<?php
class FormTwitterMessage extends mugForm
{
  public function setup()
  {
  	parent::setup();
  	
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
              'message'   => new sfWidgetFormTextarea(),
             )          
        )
      );

      // validators
      $this->setValidatorSchema(
        new sfValidatorSchema(
          array(
            'message' => new sfValidatorPass(),
          )
         )
       );  

    $this->widgetSchema->setLabels(
      array(
          'message'  => 'Tweet:',
      )
    );    
  }
}
?>