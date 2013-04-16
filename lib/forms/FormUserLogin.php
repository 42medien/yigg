<?php

/**
 * create a form for user login
 *
 * @package 	yigg
 * @subpackage  user
 */
class FormUserLogin extends yiggForm
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
          'username' => new sfWidgetFormInput(
            array(),
            array(
              'class' => 'ninjaRequired',
              'id'    => 'Username'
            )
          ),
          'password' => new sfWidgetFormInputPassword(
            array(),
            array(
              'class' => 'ninjaRequired',
              'id'	  => 'Password'
            )
          ),
          'remember' => new sfWidgetFormInputCheckbox(
            array(),
            array(
              'id'	=> 'Remember',
              'class' => 'chkBox'
            )
          ),
        )
      )
    );

    // validators
    $this->setValidatorSchema(
      new yiggValidatorLogin(
        array(
          'username'	=> new sfValidatorString(
            array(
              'required'    => true,
              'min_length'	=> 3,
              'max_length'	=> 45,
            ),
            array(
              'required'	=> 'Your username is required',
              'min_length'	=> 'Username must be bigger than 3 characters',
              'max_length'	=> 'Username must be smaller than 45 characters'
            )
          ),
          'password'	=> new sfValidatorString(
            array(),
            array('required' => 'Password is required')
          ),
          'remember'	=> new sfValidatorPass()
        ),
        array(),
        array(
          'login_error'	=> 'Nutzername oder Passwort ist falsch.',
          'user_disabled' => 'Account wurde noch nicht aktiviert oder ist gesperrt ',
          'user_deleted' => 'Benutzer wurde gelöscht. Einloggen nicht möglich.',
        )
      )
    );

    // setup base parameters
    parent::setup();
    $this->widgetSchema->setNameFormat('LoginForm[%s]');

    // set labels
    $this->widgetSchema->setLabels(
      array(
        'username' => 'Username',
        'password' => 'Password',
        'remember' => 'Remember me'
      )
    );
  }
}