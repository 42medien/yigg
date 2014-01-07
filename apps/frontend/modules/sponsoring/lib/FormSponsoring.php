<?php

/**
 * Allows a sponsoring place to be selected.
 *
 */
class FormSponsoring extends yiggForm
{

  protected $name = "FormSponsoring";

  public function setup()
  {
    parent::setup();

    $places = array();
    foreach( $this->getOption('places', array()) as $place)
    {
      $places[$place['id']] = $place;
    }

    $this->setWidgets(array(
      'image'       => new sfWidgetFormInputFile(
         array(),
         array('id' => 'Bild')),
      'image_title' => new sfWidgetFormInput(
         array(),
         array('id' => 'bildTitel')),
      'sponsor_url' => new sfWidgetFormInput(
        array(),
        array('id' => 'zielUrl')),
      'place_id'       => new sfWidgetFormSelect(
        array('choices' => $places) ,
        array(
           'onclick' => 'javascript:updatePrice();',
           'onchange' => 'javascript:updatePrice();'
        )
      ),
      'weeks' => new sfWidgetFormSelect(
        array(
          'choices' => sfConfig::get("app_sponsoring_terms")
        ),
        array(
          'onclick' => 'javascript:updatePrice();',
          'onchange' => 'javascript:updatePrice();',
        )
      ),
      'image_id'    => new sfWidgetFormInputHidden(),
    ));

    $this->setValidators(
      array(
      'image' => new sfValidatorFile(
        array(
          'required'    =>  false,
          'mime_types'  => array(
            'image/jpeg',
            'image/pjpeg',
            'image/png',
            'image/x-png',
            'image/gif',
          ),
        )
      ),
      'image_title' => new yiggValidatorPlainText(array('max_length'  => 255)),
      'image_id' => new sfValidatorInteger(array('min' => 1,'required'    => false)),
      'sponsor_url' => new sfValidatorUrl(
        array(),
        array(
          'required'    => 'Bitte eine Url eingeben.',
          'invalid'     => 'Bitte eine gültige Url eingeben.',
        )
      ),
      'place_id'    => new sfValidatorCallback(
        array(
          'callback' => $func = create_function('$validator,$value,$arguments', '
            // invalid function
            if( false === array_key_exists( $value, $arguments["places"]) )
            {
              throw new sfValidatorError($validator,$arguments["invalid_msg"] , array("value" => $value));
            }

            // value exists
            $place = Doctrine::getTable("SponsoringPlace")->findOneById( $value );
            if( false === $place->isAvailable() )
            {
              throw new sfValidatorError($validator, $arguments["unavaliable_msg"] , array("value" => $value));
            }
            return $value;
          '),
          'arguments' => array(
            "places" => $places,
            "invalid_msg" => 'Das von Ihnen gewünschte Sponsoring-Paket ist zur Zeit nicht buchbar.',
            "unavaliable_msg" =>'Der gewählte Platz ist im Moment nicht verfügbar. Bitte einen anderen wählen.',
          )
        ),
        array( 'required' => true )
      ),
      'weeks' => new sfValidatorChoice(
         array(
          'choices' => array_keys(sfConfig::get("app_sponsoring_terms")),
          "required" => true
         ),
         array(
           "required" => "Bitte geben Sie den gewünschetn Zeitraum an",
           "invalid" =>  "Bitte wählen Sie ". implode(", ",array_keys(sfConfig::get("app_sponsoring_terms"))) . " Wochen"
         )
       )
      )
    );

    $this->widgetSchema->setLabels(array(
      'image'         => 'Bild',
      'image_title'   => 'Bild Titel',
      'sponsor_url'   => 'Ziel URL',
      'place_id'      => 'Position',
      'weeks'         => 'Sponsor-Zeitraum',
    ));

    $this->widgetSchema->setHelps(array(
      'image' => '(jpg, gif, png)',
      'sponsor_url' => 'Format: "http://..."',
      'place_id' => '<a id="place_location_link" href="javascript:showSponsoringPlace();">Position anzeigen</a>'
    ));

    $this->widgetSchema->setNameFormat('FormSponsoring[%s]');
  }
}