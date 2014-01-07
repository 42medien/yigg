<?php

/**
 * create a form for password reset
 *
 * @package 	yigg
 * @subpackage  user
 */
class FormUserResetPassword extends yiggForm
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
            'id'    => 'Username',
            'placeholder' => 'Benutzername'
          )
        ),
        'email'    		=> new sfWidgetFormInput(
            array(),
            array(
              'class'	=> 'ninjaRequired',
              'id'	=> 'Email',
              'placeholder' => 'Email'
            )
          ),
          )
        )
      );

      // validators
      $this->setValidatorSchema(
         new sfValidatorSchema(
        array(
          'username'	=> new yiggValidatorUsername(
            array('unique' => false),
            array(
              'required'		=> 'Du musst einen Benutzernamen eingeben',
            )
          ),

          'email'	=> new sfValidatorEmail(
              array(),
            array(
              'required'		=> 'E-Mail ungültig',
              'invalid'       => 'E-Mail ungültig'
            )
          )
          )
        )
       );

       // setup base parameters
    parent::setup();

    $this->widgetSchema->setNameFormat('ResetPasswordForm[%s]');

    // set labels
    $this->widgetSchema->setLabels(
      array(
          'username'          => false,
          'email'             => false,
      )
    );
  }
}