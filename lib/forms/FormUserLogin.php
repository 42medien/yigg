<?php
class FormUserLogin extends yiggForm
{

  /**
   * setup form fields and validators
   */
  public function setup()
  {
    parent::setup();
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'username' => new sfWidgetFormInput(array(), array('placeholder' => 'Username', 'class' => 'ninjaRequired', "id" => "Username")),
          'password' => new sfWidgetFormInputPassword(array(), array('placeholder' => 'Passwort' ,'class' => 'ninjaRequired', "id" => "Password")),
          'remember' => new sfWidgetFormInputCheckbox(array(), array('class' => 'chkBox', "id" => "Remember")),
    )));

    $this->setValidatorSchema(
      new sfValidatorSchema(
        array(
          "username" => new sfValidatorString(array(), array('required'	=> 'Benutzername wird benötigt')),
          "password"	=> new sfValidatorString(array(), array('required' => 'Passwort wird benötigt.')),
          "remember"	=> new sfValidatorBoolean()
    )));

    $this->widgetSchema->setNameFormat('LoginForm[%s]');

    $this->widgetSchema->setLabels(
      array(
        'username' => 'Benutzername',
        'password' => 'Passwort',
        'remember' => 'Angemeldet bleiben'
      )
    );
  }
}