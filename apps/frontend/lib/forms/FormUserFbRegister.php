<?php

/**
 * create a form for user facebook registration
 *
 * @package   yigg
 * @subpackage  user
 */
class FormUserFbRegister extends yiggForm
{

    public function setup()
    {
        // form inputs
        $this->setWidgets(
            array(
                'facebook_id'  => new sfWidgetFormInputHidden(
                    array(),
                    array(
                        'id'    => 'facebook_id',
                        'value' => $this->getOption('facebook_id')
                    )
                ),
                'username'   => new sfWidgetFormInput(
                    array(),
                    array(
                        'id'    => 'Benutzername',
                        'value' => $this->getOption('username')
                    )
                ),
                'password'    => new sfWidgetFormInputPassword(
                    array(),
                    array(
                        'id'  => 'Passwort'
                    )
                ),
                'email'    => new sfWidgetFormInput(
                    array(),
                    array(
                        'id'  => 'Email',
                        'value' => $this->getOption('email'),
                        'disabled' => 'disabled'
                    )
                ),
                'acceptedTerms' => new sfWidgetFormInputCheckbox(
                    array(),
                    array(
                        'id'  => 'AcceptTerms',
                        'class' => 'chkBox'
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
                        'invalid'  => "Du musst den Nutzungsbedingungen zustimmen",
                        'required' => "Du musst den Nutzungsbedingungen zustimmen"
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
                'facebook_id'       => 'facebook_id:',
                'username'          => 'Benutzername:',
                'password'          => 'Passwort:',
                'email'             => 'Email:',
                'acceptedTerms'     => 'Nutzungsbedingungen:',
            )
        );

        $this->validatorSchema['facebook_id'] = new sfValidatorPass();
    }
}