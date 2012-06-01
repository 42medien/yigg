<?php

/**
 * form for story comments
 *
 * @package   yigg
 * @subpackage  story
 */
class FormCommentSimple extends yiggForm
{

  /**
   * setup form fields and validators
   */
  public function setup()
  {
    parent::setup();

    // form inputs
    $this->setWidgets(
      array( 'description' =>  new sfWidgetFormTextarea(array(),array("rows"=>1, "onclick"=> "this.style.height='5em';", "placeholder" => "Dein Kommentar")) )
    );

    $this->setValidators(
      array(
        'description'        => new yiggValidatorPlainText(
           array(
             'min_length'  => 5,
             'max_length'  => 4000
           ),
           array(
             'required'    => 'Bitte einen Kommentartext einfügen',
             'min_length'  => 'Dieser Kommentar war zu kurz.',
             'max_length'  => 'Dein Kommentar ist zu lang. Bitte kürze ihn auf unter %length% Zeichen.'
          )
        )
      )
    );

   $this->getWidgetSchema()->setNameFormat("SimpleComment[%s]");

    $this->getWidgetSchema()->setLabel('description', 'Dein Kommentar:');
  }
}