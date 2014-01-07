<?php

/**
 * create a form for changing user's password
 *
 * @package   yigg
 * @subpackage  user
 */
class FormUserResetPasswordWithKey extends yiggForm
{

    public function setup()
    {
        // form inputs
     $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
        'newPassword'    => new sfWidgetFormInputPassword(
            array(),
            array(
              'class'  => 'ninjaRequired',
              'id'  => 'newPassword'
            )
          ),
          'confirmPassword'    => new sfWidgetFormInputPassword(
            array(),
            array(
              'class'  => 'ninjaRequired',
              'id'  => 'confirmPassword'
            )
          ),
          )
        )
      );

      // validators
      $this->setValidatorSchema(
            new sfValidatorSchema(
                array(
                 'newPassword'  => new yiggValidatorPassword(
                     array(
                            'min_length'    => 5,
                            'max_length'   => 45,
                     ),
                     array(
                    'required'  => 'Bitte neues Passwort eintragen',
                    'min_length'  => 'Passwort muss aus mind. 5 Zeichen bestehen.',
                    'max_length'  => 'Passwort darf aus max. 45 Zeichen bestehen.',
                     )
                 ),
                 'confirmPassword'  => new yiggValidatorPassword(
                     array(
                            'min_length'    => 5,
                            'max_length'   => 45,
                     ),
                     array(
                    'required'  => 'Bitte neues Passwort bestätigen.',
                    'min_length'  => 'Passwort muss aus mind. 5 Zeichen bestehen.',
                    'max_length'  => 'Passwort darf aus max. 45 Zeichen bestehen.',
                    )
                 ),
              )
            )
      );

      // compare two passwords
      $this->validatorSchema->setPostValidator(
         new sfValidatorSchemaCompare(
              'confirmPassword',
              sfValidatorSchemaCompare::EQUAL,
              'newPassword',
              array(),
                array(
                  'invalid'  => 'Passwörter sind nicht identisch.'
                )
      ));

       // setup base parameters
    parent::setup();

    $this->widgetSchema->setNameFormat('ChangePassword[%s]');

    // set labels
    $this->widgetSchema->setLabels(
      array(
         'newPassword'       => 'Neues Passwort:',
         'confirmPassword'   => 'Neues Passwort bestätigen:',
      )
    );
  }

}