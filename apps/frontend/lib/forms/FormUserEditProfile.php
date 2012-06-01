<?php

/**
 * create a form for editing user's profile
 *
 * @package   yigg
 * @subpackage  user
 */
class FormUserEditProfile extends yiggForm
{
  // used in processRequest() for multipart attachment finding.
  protected $name = 'EditProfile';

  public function setup()
  {
    parent::setup();

    // form inputs
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'avatar' => new sfWidgetFormInputFile(
            array(),
            array(
              'class' => 'uploadField'
            )
          ),
          'sex' => new sfWidgetFormSelect(
            array(
              'choices'   => array(
                 'none'   => 'Keine Angabe',
                 'male'   => 'männlich',
                 'female' => 'weiblich',
               ),
            ),
            array()
          ),
          'about_me' => new sfWidgetFormTextarea(array(), array("placeholder" => "Erzähl uns etwas über Dich. (Max 200 Zeichen)")),
          'city' => new sfWidgetFormInput(array(), array("placeholder" => "Musterstadt, Deutschland")),
          'birthday' => new sfWidgetFormInput(array(), array("placeholder" => "27.01.1976")),
          'website' => new sfWidgetFormInput(array(), array("placeholder" => "http://yigg.de")),
          'why_yigg' => new sfWidgetFormTextarea(array(), array("placeholder" => "Warum benutzt Du YiGG? (Max 200 Zeichen)")),
          'email' => new sfWidgetFormInput(array(), array("placeholder" => "marvin@hampster.com"))
          )
        )
      );

      // validators
      $this->setValidatorSchema(
        new sfValidatorSchema(
          array(
            'avatar' => new sfValidatorFile(
              array(
                'required'  =>  false,
                 'mime_types' => array(
                   'image/jpeg',
                   'image/pjpeg',
                   'image/png',
                   'image/x-png',
                   'image/gif',
                 ),
                 'max_size' => 2000000,
              )
           ),
           'sex' => new sfValidatorChoice(
             array(
               'required' => false,
                'choices'   => array('none','male','female')
             )
           ),
           'city'   => new yiggValidatorPlainText(
             array(
               'required'          => false,
               'max_length'        => 45
             ),
             array(
              'max_length'         => 'Stadt darf nicht mehr als 45 Zeichen haben'
             )
           ),
           
           'about_me'   => new yiggValidatorPlainText(
             array(
               'required'          => false,
               'min_length'        => 3,
               'max_length'        => 200
             ),
             array(
               'min_length'         => 'Erzähl uns doch bitte etwas mehr über dich :)',
               'max_length'         => 'Über mich darf nicht mehr als 200 Zeichen lang sein.'
             )
           ),
           'why_yigg'   => new yiggValidatorPlainText(
             array(
               'required'          => false,
               'min_length'        => 3,
               'max_length'        => 200
             ),
             array(
               'max_length'         => 'Schön, dass du so uns soo toll findest. Es sind allerdings nur 200 Zeichen erlaubt.'
             )
           ),
           'website'   => new yiggValidatorURL(
             array(
               'required' => false
             ),
             array()
           ),
           'birthday' => new yiggValidatorAge(
              array(
              'required' => false,
              )
           ),
           'email' => new yiggValidatorEmail()
         )
       )
     );

       // setup base parameters
    $this->widgetSchema->setNameFormat('EditProfile[%s]');

    // set labels
    $this->widgetSchema->setLabels(
      array(
        'avatar'   => 'Avatar:',
        'sex'            => 'Geschlecht:',
        'city'          => 'Ort:',
        'birthday'  => 'Geburtstag:',
        'about_me'  => 'Über mich:',
        'why_yigg' => 'Warum YiGG:',
        'email' => "E-Mail:"
      )
    );
  }
}