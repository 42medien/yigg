<?php

/**
 * form for story comments
 *
 * @package   yigg
 * @subpackage  story
 */
class yiggPageFilter extends yiggForm
{

  /**
   * setup form fields and validators
   */
  public function setup()
  {
    parent::setup();

    $filterChoices = $this->getOption("filters");
    $sortChoices = $this->getOption("sorts");
    $this->setWidgetSchema(
      new sfWidgetFormSchema(
        array(
          'filter'   => new sfWidgetFormSelect(
            array(
              'choices' => $filterChoices,
            )
          ),
          'sort'   => new sfWidgetFormSelect(
            array(
              'choices' => $sortChoices,
            )
          )
        )
      )
    );
    if(count($filterChoices) == 0)
    {
      $this->offsetUnset("filter");
    }
    if(count($sortChoices) == 0)
    {
      $this->offsetUnset("sort");
    }
    $this->setValidatorSchema( new sfValidatorSchema(
      array( 'filter'   => new sfValidatorPass(), 'sort'   => new sfValidatorPass() ))
    );
    $this->widgetSchema->setNameFormat('PageFilter[%s]');
    $this->widgetSchema->setLabels(
      array(
        'filter' => 'Filter:',
        'sort' => 'Sort:'
      )
    );
  }

  /**
   * Returns a link suitable for the current URL
   *
   * @return unknown
   */
  public function getlink()
  {
    $params = sfContext::getInstance()->getRequest()->getParams();
    unset($params["PageFilter"]);
    unset($params["commit"]);

    return sfContext::getInstance()->getRequest()->forceParams($params);
  }
}