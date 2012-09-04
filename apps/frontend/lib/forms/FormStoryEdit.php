<?php

/**
 * create a form for creating and editing basic story inputs
 *
 * @package   yigg
 * @subpackage  story
 */
class FormStoryEdit extends yiggForm
{
  private $story;
  protected $name = 'EditStory';


  /**
   * setup form fields and validators
   */
  public function setup()
  {
      // setup base
      parent::setup();

      $story = $this->getOption("story");
      if($story)
      {
        $this->story = $story;
      }

      switch( $story->getStoryType() )
      {
        case "Article":
          $this->addArticleFields();
          $this->addArticleValidation();
        break;

        default:
          $this->addNormalFields();
          $this->addNormalValidation();
        break;
      }


      // set field names
      $this->widgetSchema->setNameFormat('EditStory[%s]');

      // set labels
      $this->widgetSchema->setLabels(
        array(
            'external_url' => 'URL der Nachricht:',
            'title'       => 'Titel:',
            'description' => 'Beschreibung:',
            'Tags'       => 'Themen:',
         )
      );
  }

  /**
   * Adds the fields for a standard story
   *
   */
  protected function addNormalFields()
  {
      $choices = Doctrine_Core::getTable('Category')->getCategories();

    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
            'external_url'   => new sfWidgetFormInput(
              array(),
              array(
                'class'  => 'ninjaValidate',
                'id'  => 'external_url',
              )
            ),
            'slider'   => new yiggWidgetFormImageSlider(
                array(),
                array(
                    'class'  => 'ninjaValidate',
                    'id'  => 'slider',
                )
            ),
            'image_slider' => new sfWidgetFormInputHidden(
                array(),
                array(
                    'class'  => 'sfValidatorUrl',
                    'id'  => 'image_slider'
                )
            ),
            'title'        => new sfWidgetFormInput(
              array(),
              array(
                'class'  => 'ninjaRequired',
                'id'  => 'Title'
              )
            ),
            'description' =>  new sfWidgetFormTextarea(
              array(),
              array('class' => $this->story->getStoryType() === "Article" ? "large":"count", "id" => "Description")
            ),
            'Tags'      => new sfWidgetFormInput(
              array(),
              array(
                'class'    => 'ninjaRequired',
                'id'    => 'Tags'
              )
            ),
            'Categories' => new sfWidgetFormChoice(
                array('choices' => $choices, 'expanded' => true)
              )
          ),
          array(),
          array(),
          array(),
          array(
            "Tags" => "(Kommagetrennt)"
          )
        )
      );
  }

  /**
   * Adds the validation for a standard story
   *
   */
  protected function addNormalValidation()
  {
    $this->setValidatorSchema(
        new sfValidatorSchema(
          array(
              'external_url'   => new yiggValidatorStoryURL(
                array( "story" => $this->story ),
                array('max_length' => "Die Url ist zu lang!")
              ),
              'title'        => new sfValidatorString(
                array(
                  'min_length' => 3,
                  'max_length' => 128
                ),
                array(
                  'required'  => 'Die Nachricht benötigt einen Titel',
                  'min_length'=> 'Dieser Titel ist zu kurz',
                  'max_length'=> 'Der Titel darf aus maximal 128 Zeichen bestehen',
                )
              ),
              'image_slider'    => new sfValidatorUrl(
                  array('required'=>false)
              ),
              'description' => new yiggValidatorPlainText(
                  array(
                    'min_length' => 10,
                  ),
                  array(
                    'required'  => 'Die Story braucht eine Beschreibung',
                    'min_length'=> 'Diese Beschreibung ist zu kurz. Bitte schreibe mindestens 10 Zeichen.',
                  )
              ),
              'Tags'  => new yiggValidatorTag(
               array('max_length' => 1000),
               array(
                 'required'  => 'Du musst dieser Nachricht Themen hinzufügen',
                 'no_tags' => 'Sorry wir konnten keine passenden Themen für %value% finden, Bitte trenne die Tags mit Kommata ab.',
                 'too_long' => 'Sorry, das Thema &quot; %value% &quot; ist zu lang',
                 'max_length' => 'Du hast zu viele Themen eingegeben.'
               )
              ),
              'Categories' => new sfValidatorChoice(
                  array(
                      'choices' => array('true', 'on', 1)
                  ),
                  array(
                      'invalid'  => "You must check 1 or more categories",
                      'required' => "You must check 1 or more categories"
                  )
              )
           )
        )
      );
  }

  /**
   * Adds the fields for a article type story
   *
   */
  protected function addArticleFields()
  {
    $this->addNormalFields();
    $this->offsetUnset("external_url");
  }

  /**
   * Adds the validation for an article story
   *
   */
  protected function addArticleValidation()
  {
    $this->addNormalValidation();
    $this->offsetUnset("external_url");
  }
}