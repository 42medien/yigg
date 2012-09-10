<?php

/**
 * create a form for user registration
 *
 * @package   yigg
 * @subpackage  user
 */
class FormUserRegister extends yiggForm
{

  public function setup()
  {
    // form inputs
    $this->setWidgets(
        array(
          'username'   => new sfWidgetFormInput(
            array(),
            array(
              'id'    => 'Benutzername',
              'placeholder' => 'Benutzername'
            )
          ),
          'password'    => new sfWidgetFormInputPassword(
            array(),
            array(
              'id'  => 'Passwort',
              'placeholder' => 'Passwort'
            )
          ),
          'email'    => new sfWidgetFormInput(
            array(),
            array(
              'id'  => 'Email',
              'placeholder' => 'Email'
            )
          ),
          'acceptedTerms' => new yiggWidgetFormInputCheckbox(
            array(),
            array(
               'id'  => 'AcceptTerms',
               'class' => 'chkBox',
               'link' => array('name' => "Nutzungsbedingungen", 'url' => "@legal_pages?template=nutzungsbedingungen")
            )
          ),
          'acceptedTermsd' => new yiggWidgetFormInputCheckbox(
                array(),
                array(
                    'id'  => 'AcceptTermsd',
                    'class' => 'chkBox',
                    'link' => array('name' => "Datenschutzbestimmungen", 'url' => "@legal_pages?template=datenschutzrichtlinien")
                )
          )
        )
    );

    // validators
    $this->setValidators(
        array(
        'username'  => new yiggValidatorUsername(
           array(
               'unique'            => true,
               'min_length'        => 3,
               'max_length'        => 45
           ),
           array(
               'min_length'        => 'Benutzername muss aus mind. 3 Zeichen bestehen.',
               'max_length'        => 'Benutzername darf aus max. 45 Zeichen bestehen.',
               'invalid_name'      => 'Bitte wählen einen anderen Benutzernamen.',
               'bad_characters'    => 'Nur Zeichen, Zahlen und Unterstriche.',
               'username_taken'    => 'Benutzername ist bereits vergeben.',
           )
        ),
       'password'  => new yiggValidatorPassword(
          array(
            'min_length'        => 5,
            'max_length'        => 45
          ),
          array(
            'min_length'         => 'Passwort muss aus mind. 5 Zeichen bestehen.',
            'max_length'         => 'Passwort darf aus max. 45 Zeichen bestehen.',
            'required'           => 'Passwort ist erforderlich.',
          )
       ),
       'email' => new yiggValidatorEmail(
          array(
            'unique'     => true,
          ),
          array(
            'invalid'    => 'Falsches E-Mail-Adressformat.',
            'required'   => 'E-Mail-Adresse ist erforderlich.',
            'not_unique' => 'Diese E-Mail-Adresse ist bereits registriert.',
            'tmp_email'  => 'Wir unterstützen keine Kurzzeit-E-Mail-Adressen.'
          )
       ),
       'acceptedTerms' => new sfValidatorChoice(
          array(
            'choices' => array('true', 'on', 1)
          ),
          array(
           'invalid'  => "Du musst den Datenschutzbestimmungen zustimmen",
           'required' => "Du musst den Datenschutzbestimmungen zustimmen"
          )
       ),
            'acceptedTermsd' => new sfValidatorChoice(
                array(
                    'choices' => array('true', 'on', 1)
                ),
                array(
                    'invalid'  => "Du musst den Datenschutzbestimmungen zustimmen",
                    'required' => "Du musst den Datenschutzbestimmungen zustimmen"
                )
            )
     )
  );

  // setup base parameters
  parent::setup();

  $this->widgetSchema->setNameFormat('RegisterForm[%s]');

  // set labels
  $this->widgetSchema->setLabels(
    array(
        'username'          => false,
        'password'          => false,
        'email'             => false,
        'acceptedTerms'     => false,
        'acceptedTermsd'     => false
      )
    );
  }
}