<?php

/**
 * create a form for invitation of a premium sponsor
 *
 * @package 	yigg
 * @subpackage  backend
 */
class premiumInvitationForm extends yiggForm
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
              'username'   => new sfWidgetFormInput(
                      array(
                      ),
                      array(
                        'class' => 'ninjaRequired',
                        'id'    => 'Username'
                      )
                    ),
              'timeframe'  	=> new sfWidgetFormInput(
                      array(),
                      array(
                        'class'	=> 'ninjaRequired',
                        'id'	=> 'Password'
                      )
                    ),
              'email' 		=> new sfWidgetFormInput(
                      array(),
                      array(
                        'class'	=> 'ninjaRequired',
                        'id'	=> 'Password'
                      )
                    ),
          )
        )
      );

      // validators
      $this->setValidatorSchema(
      new sfValidatorSchema(       
        array(
           "username" => new yiggValidatorUsername(array(
               'unique'            => true,
               'min_length'        => 3,
               'max_length'        => 45
           ),
           array(
               'min_length'        => 'Benutzername muss aus mind. 3 Zeichen bestehen.',
               'max_length'        => 'Benutzername darf aus max. 45 Zeichen bestehen.',
               'invalid_name'      => 'Bitte wählen einen anderen Benutzernamen.',
               'bad_characters'    => 'Nur Zeichen, Zahlen und Unterstriche.',
               'username_taken'    => 'Benutzername ist bereits vergeben.'
           )
           ),
          'email'	=> new yiggValidatorEmail(),
          "timeframe" => new sfValidatorDate(
             array(),
             array()
           )
        ))               
       );

       // setup base parameters
    parent::setup();

    $this->widgetSchema->setNameFormat('login[%s]');

    // set labels
    $this->widgetSchema->setLabels(
      array(
          'username'          => 'Username',
          'timeframe'		  =>'Enddatum:',
          'email'             => 'E-Mail'
      )
    );
  }
}
?>